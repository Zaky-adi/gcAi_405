import cv2
import time
import requests
import sys
import threading
from ultralytics import YOLO
from flask import Flask, Response

# 1. INISIALISASI
app = Flask(__name__)
model = YOLO('yolov8n.pt') 

# --- KONFIGURASI PENTING ---
ESP32_STREAM_URL = "http://192.168.100.14:81/stream" 
GRAPHQL_URL = "http://127.0.0.1:8000/graphql"

API_EMAIL = "admin@sistem.com"
API_PASSWORD = "admin123"

TOKEN = None
HEADERS = {
    "Content-Type": "application/json"
}

COOLDOWN_TIME = 4.0 
last_detection_time = 0

# ==========================================
# KELAS BARU UNTUK MENGHAPUS BUFFER VIDEO
# ==========================================
class VideoStreamWidget:
    def __init__(self, src=0):
        self.stream = cv2.VideoCapture(src)
        self.stream.set(cv2.CAP_PROP_BUFFERSIZE, 1) # Paksa antrean menjadi 1
        (self.grabbed, self.frame) = self.stream.read()
        self.stopped = False

    def start(self):
        # Jalankan pembacaan video di background thread
        threading.Thread(target=self.update, args=(), daemon=True).start()
        return self

    def update(self):
        while True:
            if self.stopped:
                self.stream.release()
                return
            # Terus ambil gambar terbaru tanpa henti
            (self.grabbed, self.frame) = self.stream.read()

    def read(self):
        return self.grabbed, self.frame

    def stop(self):
        self.stopped = True

# ==========================================

def login_ke_server():
    global TOKEN, HEADERS
    print("Mencoba login ke server Laravel...")
    query = """
    mutation Login($email: String!, $password: String!) {
      login(email: $email, password: $password) {
        token
      }
    }
    """
    variables = {"email": API_EMAIL, "password": API_PASSWORD}
    try:
        response = requests.post(GRAPHQL_URL, json={"query": query, "variables": variables}, headers=HEADERS)
        result = response.json()
        if "data" in result and result["data"]["login"]:
            TOKEN = result["data"]["login"]["token"]
            HEADERS["Authorization"] = f"Bearer {TOKEN}"
            print("[+] Login Berhasil! Token aktif.")
            return True
        else:
            print("[-] Login Gagal. Periksa kembali email dan password.")
            return False
    except Exception as e:
        print("[-] Server tidak dapat dihubungi. Error:", e)
        return False

def send_to_database(vehicle_type, confidence):
    query = """
    mutation CreateLog($device_id: String!, $vehicle_type: String!, $confidence_score: Float!) {
      createVehicleLog(device_id: $device_id, vehicle_type: $vehicle_type, confidence_score: $confidence_score) {
        id
        vehicle_type
      }
    }
    """
    variables = {
        "device_id": "ESP32-CAM-GERBANG",
        "vehicle_type": vehicle_type,
        "confidence_score": float(confidence)
    }
    try:
        response = requests.post(GRAPHQL_URL, json={"query": query, "variables": variables}, headers=HEADERS)
        if response.status_code == 200 and "errors" not in response.json():
            print(f"[+] API Sukses: {vehicle_type} terkirim!")
    except Exception as e:
        pass

def generate_frames():
    global last_detection_time
    
    print(f"Menyambungkan ke: {ESP32_STREAM_URL} ...")
    # Panggil fungsi multi-threading di sini
    video_stream = VideoStreamWidget(ESP32_STREAM_URL).start()
    time.sleep(1.0) # Tunggu kamera stabil
    
    frame_count = 0
    last_detected_boxes = [] 
    
    while True:
        success, frame = video_stream.read()
        
        if not success or frame is None:
            time.sleep(0.05)
            continue
            
        frame = cv2.resize(frame, (640, 480))
        frame_count += 1
        vehicle_detected = None
        highest_conf = 0.0

        if frame_count % 3 == 0:
            results = model(frame, stream=True, verbose=False)
            last_detected_boxes = [] 
            
            for r in results:
                for box in r.boxes:
                    x1, y1, x2, y2 = map(int, box.xyxy[0])
                    cls = int(box.cls[0])
                    conf = float(box.conf[0])
                    
                    label = ""
                    if cls == 2:
                        label = "mobil"
                    elif cls in [5, 7]:
                        label = "truck"
                    
                    if label and conf > 0.5:
                        if conf > highest_conf:
                            highest_conf = conf
                            vehicle_detected = label
                        warna = (0, 255, 0) if label == "mobil" else (0, 165, 255)
                        last_detected_boxes.append({"coords": (x1, y1, x2, y2), "label": label, "conf": conf, "warna": warna})

        for box_data in last_detected_boxes:
            x1, y1, x2, y2 = box_data["coords"]
            warna = box_data["warna"]
            label_teks = f"{box_data['label'].upper()} {int(box_data['conf']*100)}%"
            cv2.rectangle(frame, (x1, y1), (x2, y2), warna, 2)
            cv2.putText(frame, label_teks, (x1, max(15, y1 - 10)), cv2.FONT_HERSHEY_SIMPLEX, 0.6, warna, 2)

        current_time = time.time()
        if vehicle_detected and (current_time - last_detection_time > COOLDOWN_TIME):
            print(f">>> Deteksi objek: {vehicle_detected} ({int(highest_conf*100)}%)")
            send_to_database(vehicle_detected, highest_conf)
            last_detection_time = current_time

        ret, buffer = cv2.imencode('.jpg', frame, [int(cv2.IMWRITE_JPEG_QUALITY), 70])
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + buffer.tobytes() + b'\r\n')

@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    login_berhasil = login_ke_server()
    if not login_berhasil:
        sys.exit()
    app.run(host='0.0.0.0', port=5000, debug=False, threaded=True)