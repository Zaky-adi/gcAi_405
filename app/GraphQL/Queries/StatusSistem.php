<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final readonly class StatusSistem
{
    public function __invoke($_, array $args): array
    {
        // 1. Inisialisasi Firestore
        $firestore = app('firebase.firestore')->database();
        
        // 2. Ambil data 'detak jantung' dari collection 'system_health'
        $healthDocs = $firestore->collection('system_health')->documents();
        
        $healthData = [];
        foreach ($healthDocs as $doc) {
            $healthData[$doc->id()] = $doc->data();
        }

        // 3. Tentukan batas waktu toleransi (5 Menit)
        // Jika alat tidak lapor selama lebih dari 5 menit, anggap OFFLINE.
        $batasWaktu = Carbon::now()->subMinutes(5);

        // 4. Daftar komponen sistem dasar
        // Key array (pi, cctv, dll) akan menjadi nama Document ID di Firestore
        $komponen = [
            'mqtt' => ['id' => '1', 'nama' => 'Koneksi IoT', 'deskripsi' => 'MQTT Broker'],
            'cctv' => ['id' => '2', 'nama' => 'Kamera CCTV', 'deskripsi' => 'Webcam Full HD 1080p'],
            'yolo' => ['id' => '3', 'nama' => 'Model AI (YOLO)', 'deskripsi' => 'Virtual Line Counting'],
            'pi'   => ['id' => '4', 'nama' => 'Edge Device', 'deskripsi' => 'Raspberry Pi 4 Model B'],
            'db'   => ['id' => '5', 'nama' => 'Database Server', 'deskripsi' => 'Firebase Cloud Firestore'],
        ];

        $results = [];

        foreach ($komponen as $key => $item) {
            $isActive = false;
            $statusText = 'Offline';

            if ($key === 'db') {
                // Self-check khusus Database: 
                // Jika script ini berhasil berjalan dan membaca data, otomatis DB sedang hidup.
                $isActive = true;
                $statusText = 'Terhubung';
            } else {
                // Pengecekan Dinamis untuk Edge Device, CCTV, YOLO, dan MQTT
                if (isset($healthData[$key]['last_seen'])) {
                    // Parsing waktu last_seen dari Firestore
                    $lastSeen = Carbon::parse($healthData[$key]['last_seen']);
                    
                    // Apakah waktu lapor terakhir lebih baru dari batas waktu (5 menit lalu)?
                    if ($lastSeen->greaterThanOrEqualTo($batasWaktu)) {
                        $isActive = true;
                        // Sesuaikan teks status agar enak dibaca
                        $statusText = ($key === 'cctv' || $key === 'yolo') ? 'Aktif' : 'Online';
                    }
                }
            }

            $results[] = [
                'id' => $item['id'],
                'nama' => $item['nama'],
                'deskripsi' => $item['deskripsi'],
                'status_text' => $statusText,
                'is_active' => $isActive,
            ];
        }

        return $results;
    }
}