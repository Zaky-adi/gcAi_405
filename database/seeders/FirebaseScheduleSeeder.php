<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inisialisasi instance Firestore
        $firestore = Firebase::firestore();
        $database = $firestore->database();

        $gateId = 'pbl_gate_utama';
        $now = gmdate('Y-m-d\TH:i:s\Z'); // Format UTC ISO 8601

        // 1. Inisialisasi Dokumen Jadwal Gerbang
        // Menggunakan set() agar ID dokumennya tetap "pbl_gate_utama"
        $database->collection('gate_schedules')->document($gateId)->set([
            'gate_name'   => 'Gerbang Utama',
            'start_time'  => '06:00',
            'end_time'    => '18:00',
            'active_days' => ['senin', 'selasa', 'rabu', 'kamis'],
            'is_standby'  => false,
            'updated_at'  => $now
        ]);

        $this->command->info("Dokumen jadwal gerbang berhasil diinisialisasi di Firebase.");

        // 2. Inisialisasi Log Pertama
        // Menggunakan add() agar ID dokumen digenerate otomatis oleh Firebase
        $database->collection('schedule_logs')->add([
            'gate_id'    => $gateId,
            'action'     => 'Inisialisasi Sistem',
            'details'    => 'Sistem jadwal Firebase berhasil dibuat',
            'created_at' => $now
        ]);

        $this->command->info("Log inisialisasi berhasil ditambahkan.");
    }
}