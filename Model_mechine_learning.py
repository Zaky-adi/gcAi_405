import cv2
import requests
from ultralytics import YOLO

# 1. Inisialisasi Model YOLOv8 (menggunakan versi nano agar ringan di edge device)
model = YOLO('yolov8n.pt')

# 2. Konfigurasi Video Input
video_path = 'sampel_gerbang.mp4' # Ganti dengan 0 jika ingin menggunakan webcam/CCTV langsung
cap = cv2.VideoCapture(video_path)

# 3. Konfigurasi Virtual Line & Variabel Penghitung
# Sesuaikan posisi Y (garis horizontal) berdasarkan resolusi videomu
virtual_line_y = 350 
counted_ids = set()
total_kendaraan = 0

# URL API Laravel lokal/production untuk menerima data POST
API_URL = "http://localhost:8000/api/kendaraan/count" 

# ID Class pada dataset COCO untuk kendaraan: 2 (Mobil), 3 (Motor), 5 (Bus), 7 (Truk)
vehicle_classes = [2, 3, 5, 7]

def kirim_data_ke_api(jenis_kendaraan, total):
    """Fungsi untuk mengirim data ke backend (Laravel)"""
    payload = {
        "jenis_kendaraan": jenis_kendaraan,
        "total_sementara": total
    }
    try:
        # Mengirim POST request tanpa menghentikan proses deteksi video
        response = requests.post(API_URL, json=payload, timeout=2)
        print(f"Data terkirim ke API: {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"Gagal mengirim data: {e}")

while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    # 4. Melakukan Tracking Objek
    # persist=True mengaktifkan tracking antar frame
    results = model.track(frame, classes=vehicle_classes, persist=True, tracker="bytetrack.yaml", verbose=False)

    # Menggambar Virtual Line berwarna biru
    cv2.line(frame, (0, virtual_line_y), (frame.shape[1], virtual_line_y), (255, 0, 0), 2)

    if results[0].boxes.id is not None:
        boxes = results[0].boxes.xyxy.cpu().numpy() # Koordinat bounding box (x1, y1, x2, y2)
        track_ids = results[0].boxes.id.cpu().numpy().astype(int) # ID unik dari tracker
        class_ids = results[0].boxes.cls.cpu().numpy().astype(int) # ID jenis objek

        for box, track_id, class_id in zip(boxes, track_ids, class_ids):
            x1, y1, x2, y2 = box
            
            # Mencari titik tengah bawah kendaraan (Center Y)
            cx = int((x1 + x2) / 2)
            cy = int(y2) 

            # Menggambar bounding box dan titik tengah
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), (0, 255, 0), 2)
            cv2.circle(frame, (cx, cy), 5, (0, 0, 255), -1)
            cv2.putText(frame, f"ID: {track_id}", (int(x1), int(y1) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

            # 5. Logika Penghitungan (Crossing Line)
            # Jika titik tengah kendaraan melewati virtual line dan ID belum pernah dihitung
            if cy > virtual_line_y and track_id not in counted_ids:
                counted_ids.add(track_id)
                total_kendaraan += 1
                
                # Mendapatkan nama kendaraan berdasarkan class_id (misal: 'car', 'motorcycle')
                jenis = model.names[class_id]
                
                # Menjalankan fungsi kirim ke API secara asinkron (atau langsung untuk simulasi ini)
                kirim_data_ke_api(jenis, total_kendaraan)

    # Menampilkan teks total hitungan di layar
    cv2.putText(frame, f"Total Kendaraan: {total_kendaraan}", (20, 50), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 3)

    # Menampilkan hasil visualisasi
    cv2.imshow("Sistem Penghitung Kendaraan", frame)

    # Tekan 'q' untuk keluar dari program
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()