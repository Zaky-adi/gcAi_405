import paho.mqtt.client as mqtt
import json
import time
import random
from datetime import datetime

# Konfigurasi MQTT
BROKER = "broker.hivemq.com"
PORT = 1883
TOPIC = "polibatam/gatecontrol/vehicle/logs" # Topik khusus gerbang
DEVICE_ID = "Kamera_Gerbang_Utama_Virtual"

def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print(f"[+] Berhasil terhubung ke MQTT Broker ({BROKER})")
    else:
        print(f"[-] Gagal terhubung, kode error: {rc}")

# Inisialisasi Klien MQTT
client = mqtt.Client("Kamera_Virtual_001")
client.on_connect = on_connect
client.connect(BROKER, PORT, 60)

client.loop_start()

kendaraan = ['mobil', 'truck', 'pickup', 'motor']

print("=== MEMULAI SIMULASI AI KAMERA GERBANG ===")
print("Menunggu kendaraan lewat (mengirim data setiap 5-10 detik)...\n")

try:
    while True:
        # Jeda waktu acak seolah-olah menunggu kendaraan lewat
        time.sleep(random.randint(5, 10))

        # Membuat data deteksi palsu
        payload = {
            "device_id": DEVICE_ID,
            "vehicle_type": random.choice(kendaraan),
            "confidence_score": round(random.uniform(0.70, 0.98), 2),
            "detected_at": datetime.utcnow().strftime('%Y-%m-%dT%H:%M:%S+00:00')
        }

        # Mengubah dictionary Python menjadi string JSON
        pesan_json = json.dumps(payload)

        # Mengirim (Publish) data ke MQTT
        client.publish(TOPIC, pesan_json)
        print(f"[>] Deteksi terkirim: {pesan_json}")

except KeyboardInterrupt:
    print("\n[!] Simulasi dihentikan.")
    client.loop_stop()
    client.disconnect()
