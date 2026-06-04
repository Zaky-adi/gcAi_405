<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DummyKendaraan2 extends Seeder
{
    public function run(): void
    {
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');
        $batch = $firestore->batch();

        // ==========================================
        // 1. DATA MINGGU INI (Total: 60 Data)
        // ==========================================
        $mingguIni = [];
        $jenis = ['mobil', 'truk'];
        
        for ($i = 0; $i < 60; $i++) {
            // Acak waktu dari Senin awal minggu ini sampai sekarang
            $randomDays = rand(0, Carbon::now()->dayOfWeekIso - 1);
            $randomHours = rand(0, 23);
            $randomMinutes = rand(0, 59);
            
            $createdAt = Carbon::now()->startOfWeek()
                ->addDays($randomDays)
                ->addHours($randomHours)
                ->addMinutes($randomMinutes)
                ->toIso8601String();

            $mingguIni[] = [
                'vehicle_type' => $jenis[array_rand($jenis)],
                'confidence_score' => rand(70, 99) / 100, // Opsional: Tambahan random score 0.70 - 0.99
                'created_at' => $createdAt
            ];
        }

        // ==========================================
        // 2. DATA MINGGU LALU (Total: 40 Data)
        // ==========================================
        $mingguLalu = [];
        
        for ($i = 0; $i < 40; $i++) {
            // Acak waktu di minggu lalu (Senin - Minggu)
            $randomDays = rand(0, 6);
            $randomHours = rand(0, 23);
            $randomMinutes = rand(0, 59);
            
            $createdAt = Carbon::now()->subWeek()->startOfWeek()
                ->addDays($randomDays)
                ->addHours($randomHours)
                ->addMinutes($randomMinutes)
                ->toIso8601String();

            $mingguLalu[] = [
                'vehicle_type' => $jenis[array_rand($jenis)],
                'confidence_score' => rand(70, 99) / 100, 
                'created_at' => $createdAt
            ];
        }

        // Gabungkan semua data
        $dummyData = array_merge($mingguIni, $mingguLalu);

        // ==========================================
        // 3. EKSEKUSI BATCH INSERT
        // ==========================================
        $count = 0;
        foreach ($dummyData as $data) {
            $documentRef = $collection->newDocument();
            $batch->set($documentRef, $data);
            $count++;

            // Firestore Batch punya limit 500. 
            // Meskipun kita hanya 100, ini best practice jika ingin nambah data lagi.
            if ($count % 400 === 0) {
                $batch->commit();
                $batch = $firestore->batch(); // Mulai batch baru
            }
        }

        // Eksekusi sisa batch
        $batch->commit();

        $this->command->info('100 Data dummy (60 Minggu Ini, 40 Minggu Lalu) berhasil dimasukkan ke Firestore!');
    }
}