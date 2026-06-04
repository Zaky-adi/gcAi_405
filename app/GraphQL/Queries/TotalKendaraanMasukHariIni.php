<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final class TotalKendaraanMasukHariIni
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): int
    {
        // 1. Inisialisasi koneksi ke Firestore
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // 2. Tentukan batas waktu HARI INI (dari 00:00:00 sampai besok 00:00:00)
        // Format ISO8601 digunakan agar sesuai dengan format yang di-input oleh Seeder sebelumnya
        $startOfDay = Carbon::today()->toIso8601String();
        $endOfDay = Carbon::tomorrow()->toIso8601String();

        // 3. Lakukan query ke Firestore
        $documents = $collection
            ->where('created_at', '>=', $startOfDay)
            ->where('created_at', '<', $endOfDay)
            ->documents();

        // 4. Hitung dan kembalikan total dokumen (kendaraan) yang ditemukan
        return $documents->size();
    }
}