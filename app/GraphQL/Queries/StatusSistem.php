<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class StatusSistem
{
    public function __invoke($_, array $args): array
    {
        // Di sistem IoT produksi, status "is_active" di bawah ini biasanya 
        // dicek secara dinamis (misal: cek koneksi database, atau cek waktu 
        // "last_seen" dari perangkat Raspberry Pi di database).
        // Untuk sekarang, kita buat datanya statis agar UI bisa langsung terhubung.

        return [
            [
                'id' => '1',
                'nama' => 'Koneksi IoT',
                'deskripsi' => 'MQTT Broker',
                'status_text' => 'Terhubung',
                'is_active' => true,
            ],
            [
                'id' => '2',
                'nama' => 'Kamera CCTV',
                'deskripsi' => 'Webcam Full HD 1080p',
                'status_text' => 'Aktif',
                'is_active' => true,
            ],
            [
                'id' => '3',
                'nama' => 'Model AI (YOLO)',
                'deskripsi' => 'Virtual Line Counting',
                'status_text' => 'Running',
                'is_active' => true,
            ],
            [
                'id' => '4',
                'nama' => 'Edge Device',
                'deskripsi' => 'Raspberry Pi 4 Model B',
                'status_text' => 'Online',
                'is_active' => true,
            ],
            [
                'id' => '5',
                'nama' => 'Database Server',
                'deskripsi' => 'Firebase Cloud Firestore', // Sesuaikan dengan DB yang dipakai
                'status_text' => 'Terhubung',
                'is_active' => true,
            ],
        ];
    }
}