import requests
import json
import time
import random

# ==========================================
# KONFIGURASI HOSTING
# ==========================================
# Ganti dengan URL domain hosting kamu yang sebenarnya!
GRAPHQL_URL = "https://project-31vv3.vercel.app/graphql" 
DEVICE_ID = "Kamera_Gerbang_Utama_Virtual"

print("=== MEMULAI SIMULASI AI KAMERA GERBANG ===")
print(f"Target Server : {GRAPHQL_URL}")
print("Menunggu kendaraan lewat (mengirim data setiap 5-10 detik)...\n")

kendaraan_list = ['mobil', 'truck/pickup', 'bus', 'motor']

try:
    while True:
        # Jeda waktu acak seolah-olah menunggu kendaraan lewat
        time.sleep(random.randint(5, 10))
        
        jenis_kendaraan = random.choice(kendaraan_list)
        akurasi = round(random.uniform(0.70, 0.98), 2)
        
        # Menyusun Query GraphQL Mutation sesuai schema.graphql kamu
        graphql_query = """
            mutation CreateLog($deviceId: String!, $vehicleType: String!, $confidence: Float!) {
                createVehicleLog(device_id: $deviceId, vehicle_type: $vehicleType, confidence_score: $confidence) {
                    id
                    vehicle_type
                    detected_at
                }
            }
        """
        
        variables = {
            "deviceId": DEVICE_ID,
            "vehicleType": jenis_kendaraan,
            "confidence": akurasi
        }
        
        payload = {
            "query": graphql_query,
            "variables": variables
        }
        
        headers = {
            "Content-Type": "application/json",
            "Accept": "application/json"
            # Jika endpoint createVehicleLog butuh token, tambahkan di sini:
            # "Authorization": "Bearer TOKEN_KAMU_DI_SINI"
        }
        
        try:
            # Mengirim data langsung ke server Laravel kamu
            response = requests.post(GRAPHQL_URL, json=payload, headers=headers, timeout=10)
            result = response.json()
            
            if "errors" in result:
                print(f"[-] Gagal mengirim ({jenis_kendaraan}): {result['errors'][0]['message']}")
            else:
                print(f"[+] Deteksi terkirim ke server: {jenis_kendaraan.upper()} (Akurasi: {akurasi*100:.0f}%)")
                
        except requests.exceptions.RequestException as e:
            print(f"[!] Server tidak merespons: {e}")

except KeyboardInterrupt:
    print("\n[!] Simulasi dihentikan.")