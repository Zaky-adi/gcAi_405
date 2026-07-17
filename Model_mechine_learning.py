import cv2
import time
import sys
import threading
import datetime
from ultralytics import YOLO
from flask import Flask, Response

# --- 1. INISIALISASI FIREBASE ---
import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore

print("Menghubungkan ke Firebase...")
try:
    # Ganti kembali path json di bawah ini jika posisinya berbeda di laptopmu
    cred = credentials.Certificate(r"storage\gcai-405-firebase-adminsdk-fbsvc-1fb374e19f.json")
    firebase_admin.initialize_app(cred)
    db = firestore.client()
    print("[+] Firebase berhasil terhubung!")
except Exception as e:
    print("[-] Gagal terhubung ke Firebase. Pastikan file firebase_key.json ada.")
    print("Error:", e)
    sys.exit()

# --- 2. INISIALISASI FLASK & YOLO ---
app = Flask(__name__)
model = YOLO('yolov8n.pt') 

# --- KONFIGURASI KAMERA ---
RASPBERRY_STREAM_URL = "http://192.168.10.1:5000/" 

# ==========================================
# KELAS MENGHAPUS BUFFER VIDEO (MULTI-THREAD)
# ==========================================
class VideoStreamWidget:
    def __init__(self, src=0):
        self.stream = cv2.VideoCapture(src)
        self.stream.set(cv2.CAP_PROP_BUFFERSIZE, 1) 
        (self.grabbed, self.frame) = self.stream.read()
        self.stopped = False

    def start(self):
        threading.Thread(target=self.update, args=(), daemon=True).start()
        return self

    def update(self):
        while True:
            if self.stopped:
                self.stream.release()
                return
            (self.grabbed, self.frame) = self.stream.read()

    def read(self):
        return self.grabbed, self.frame

    def stop(self):
        self.stopped = True

# ==========================================
# FUNGSI KIRIM KE FIREBASE
# ==========================================
def send_to_firebase(vehicle_type, confidence, arah, track_id):
    # Karena backend Laravel menghitung semua log sebagai kendaraan masuk,
    # kita wajib memblokir data "KELUAR" agar tidak terkirim ke database.
    if arah != "MASUK":
        return

    try:
        doc_ref = db.collection('vehicle_logs').document()
        
        # Membuat format waktu ISO8601 agar cocok dengan Carbon di Laravel[cite: 8, 11]
        waktu_iso = datetime.datetime.now().astimezone().replace(microsecond=0).isoformat()

        # Menyesuaikan nama key dengan skema GraphQL Laravel[cite: 5]
        doc_ref.set({
            'device_id': 'RASPBERRY-PI-CAM',
            'vehicle_type': vehicle_type,        # Akan terbaca sebagai 'mobil' atau 'truck'[cite: 13, 14]
            'confidence_score': float(confidence),
            'created_at': waktu_iso
        })
        print(f"    [+] GraphQL Sinkron: Data {vehicle_type.upper()} (MASUK) tersimpan!")
    except Exception as e:
        print(f"    [-] Firebase Error: {e}")

# ==========================================

def generate_frames():
    print(f"Menyambungkan ke Kamera Raspberry Pi di {RASPBERRY_STREAM_URL}...")
    video_stream = VideoStreamWidget(RASPBERRY_STREAM_URL).start() 
    time.sleep(1.0) 
    
    frame_count = 0
    last_detected_boxes = [] 
    
    # --- VARIABEL VIRTUAL LINE COUNTING (VERTIKAL) ---
    GARIS_X = 320 # Posisi garis tegak (Resolusi lebar 640, jadi 320 pas di tengah layar)
    track_history = {} # Mengingat posisi X kendaraan sebelumnya {id_kendaraan: posisi_x}
    counted_ids = set() 
    
    while True:
        success, frame = video_stream.read()
        
        if not success or frame is None:
            time.sleep(0.05)
            continue
            
        frame = cv2.resize(frame, (640, 480))
        frame = cv2.convertScaleAbs(frame, alpha=1.2, beta=40)
        frame_count += 1

        # 1. GAMBAR GARIS VIRTUAL MERAH DI LAYAR (VERTIKAL)
        cv2.line(frame, (GARIS_X, 0), (GARIS_X, 480), (0, 0, 255), 2)
        cv2.putText(frame, "BATAS AREA", (GARIS_X + 10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 2)

        if frame_count % 3 == 0:
            results = model.track(frame, persist=True, verbose=False)
            last_detected_boxes = [] 
            
            for r in results:
                if r.boxes.id is None:
                    continue

                boxes = r.boxes
                for i in range(len(boxes)):
                    box = boxes[i]
                    x1, y1, x2, y2 = map(int, box.xyxy[0])
                    cls = int(box.cls[0])
                    conf = float(box.conf[0])
                    track_id = int(r.boxes.id[i])
                    
                    label = ""
                    if cls == 2:
                        label = "mobil"
                    elif cls in [5, 7]:
                        label = "truck"
                    
                    if label and conf > 0.5:
                        # Cari titik tengah (centroid)
                        cy = int((y1 + y2) / 2)
                        cx = int((x1 + x2) / 2)

                        # 3. LOGIKA KIRI & KANAN (MASUK & KELUAR)
                        arah = None
                        if track_id in track_history:
                            last_x = track_history[track_id]

                            if track_id not in counted_ids:
                                # Dari KIRI ke KANAN garis -> MASUK
                                if last_x < GARIS_X and cx >= GARIS_X:
                                    arah = "MASUK"
                                # Dari KANAN ke KIRI garis -> KELUAR
                                elif last_x > GARIS_X and cx <= GARIS_X:
                                    arah = "KELUAR"

                                if arah:
                                    print(f"\n>>> KENDARAAN {arah}: {label.upper()} (ID: {track_id})")
                                    send_to_firebase(label, conf, arah, track_id)
                                    counted_ids.add(track_id)

                        # Simpan posisi X terbaru
                        track_history[track_id] = cx 

                        warna = (0, 255, 0) if label == "mobil" else (0, 165, 255)
                        last_detected_boxes.append({
                            "coords": (x1, y1, x2, y2), 
                            "label": label, 
                            "conf": conf, 
                            "warna": warna,
                            "id": track_id,
                            "centroid": (cx, cy)
                        })

        # 4. GAMBAR HASIL KE VIDEO
        for box_data in last_detected_boxes:
            x1, y1, x2, y2 = box_data["coords"]
            cx, cy = box_data["centroid"]
            warna = box_data["warna"]
            track_id = box_data["id"]
            
            label_teks = f"ID:{track_id} {box_data['label'].upper()} {int(box_data['conf']*100)}%"
            cv2.rectangle(frame, (x1, y1), (x2, y2), warna, 2)
            cv2.putText(frame, label_teks, (x1, max(15, y1 - 10)), cv2.FONT_HERSHEY_SIMPLEX, 0.6, warna, 2)
            
            cv2.circle(frame, (cx, cy), 5, (255, 0, 0), -1) 

        ret, buffer = cv2.imencode('.jpg', frame, [int(cv2.IMWRITE_JPEG_QUALITY), 70])
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + buffer.tobytes() + b'\r\n')

@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    print("Memulai Server AI Vision pada port 5000...")
    app.run(host='0.0.0.0', port=5000, debug=False, threaded=True)