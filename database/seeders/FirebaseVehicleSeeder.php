<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Kreait\Laravel\Firebase\Facades\Firebase; 
// Atau gunakan dependency injection tergantung cara kamu inisialisasi Firebase di Laravel

class FirebaseVehicleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Inisialisasi koneksi Firestore
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');
        
        // 2. Mulai sesi Batch
        $batch = $firestore->batch();

        // 3. Siapkan Data Dummy (Campuran hari ini dan kemarin)
        $dummyData = [
            // Data HARI INI
            ['vehicle_type' => 'mobil', 'created_at' => Carbon::now()->toIso8601String()],
            ['vehicle_type' => 'motor', 'created_at' => Carbon::now()->subHours(2)->toIso8601String()],
            ['vehicle_type' => 'truk', 'created_at' => Carbon::now()->subMinutes(30)->toIso8601String()],
            
            // Data KEMARIN (Tidak boleh terhitung oleh endpoint nantinya)
            ['vehicle_type' => 'mobil', 'created_at' => Carbon::yesterday()->toIso8601String()],
            ['vehicle_type' => 'motor', 'created_at' => Carbon::now()->subDays(2)->toIso8601String()],
        ];

        // 4. Masukkan data ke dalam antrean Batch
        foreach ($dummyData as $data) {
            // Membuat document reference baru dengan ID otomatis
            $documentRef = $collection->newDocument();
            $batch->set($documentRef, $data);
        }

        // 5. Eksekusi semua data sekaligus ke Firebase
        $batch->commit();

        $this->command->info('Data dummy berhasil dimasukkan ke Firestore!');
    }
}