<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Cloud\Firestore\FieldValue;

class InitFirebaseCommand extends Command
{
    protected $signature = 'firebase:init';
    protected $description = 'Memigrasikan skema awal sistem parkir & deteksi kendaraan ke Firestore';

    public function handle()
    {
        $this->info('Menghubungkan ke Firebase Cloud Firestore...');
        $firestore = Firebase::firestore()->database();

        // 1. Inisialisasi User (Data profil saja, Autentikasi tetap di Firebase Auth)
        $this->info('Membangun collection: users...');
        $userRef = $firestore->collection('users')->document('user_admin_1');
        $userRef->set([
            'name'       => 'Admin Sistem',
            'email'      => 'admin@sistem.com',
            'role'       => 'admin',
            'created_at' => FieldValue::serverTimestamp(),
        ]);

        // 2. Inisialisasi Devices (Kamera AI)
        $this->info('Membangun collection: devices...');
        $deviceRef = $firestore->collection('devices')->document('cam_gerbang_polibatam');
        $deviceRef->set([
            'device_name' => 'Kamera Utama',
            'location'    => 'Portal Gerbang Utama Polibatam',
            'ip_address'  => encrypt('192.168.1.100'),
            'rtsp_url'    => encrypt('rtsp://admin:admin123@192.168.1.100:554/stream'),
            'mac_address' => '00:1A:2B:3C:4D:5E',
            'is_active'   => true,
            'created_at'  => FieldValue::serverTimestamp(),
        ]);

        // 3. Inisialisasi Parking Lots
        $this->info('Membangun collection: parking_lots...');
        $parkingRef = $firestore->collection('parking_lots')->document('area_utama');
        $parkingRef->set([
            'device_id'        => 'cam_gerbang_polibatam',
            'area_name'        => 'Parkir Motor & Mobil',
            'total_capacity'   => 150,
            'current_occupied' => 0,
            'updated_at'       => FieldValue::serverTimestamp(),
        ]);

        // 4. Inisialisasi Vehicle Logs (Data dummy untuk struktur)
        $this->info('Membangun collection: vehicle_logs...');
        $logRef = $firestore->collection('vehicle_logs')->newDocument();
        $logRef->set([
            'device_id'        => 'cam_gerbang_polibatam',
            'vehicle_type'     => 'Motor',
            'confidence_score' => 95.5,
            'detected_at'      => FieldValue::serverTimestamp(),
        ]);

        $this->info('🚀 Migrasi Selesai! Skema database Anda sudah aktif di Firebase.');
        return Command::SUCCESS;
    }
}