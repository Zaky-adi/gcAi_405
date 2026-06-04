<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final readonly class TotalKendaraanMasukMingguIni
{
    public function __invoke($_, array $args): array
    {
        // 1. Inisialisasi Firestore
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // 2. Tentukan Rentang Waktu MINGGU INI (Senin 00:00 - Minggu 23:59)
        $awalMingguIni = Carbon::now()->startOfWeek()->toIso8601String();
        $akhirMingguIni = Carbon::now()->endOfWeek()->toIso8601String();

        // 3. Tentukan Rentang Waktu MINGGU LALU
        $awalMingguLalu = Carbon::now()->subWeek()->startOfWeek()->toIso8601String();
        $akhirMingguLalu = Carbon::now()->subWeek()->endOfWeek()->toIso8601String();

        // 4. Query data MINGGU INI dari Firestore
        $dataMingguIni = $collection
            ->where('created_at', '>=', $awalMingguIni)
            ->where('created_at', '<=', $akhirMingguIni)
            ->documents()
            ->size();

        // 5. Query data MINGGU LALU dari Firestore
        $dataMingguLalu = $collection
            ->where('created_at', '>=', $awalMingguLalu)
            ->where('created_at', '<=', $akhirMingguLalu)
            ->documents()
            ->size();

        // 6. Hitung Persentase Kenaikan/Penurunan
        $persentase = 0;
        
        if ($dataMingguLalu > 0) {
            $persentase = (($dataMingguIni - $dataMingguLalu) / $dataMingguLalu) * 100;
        } elseif ($dataMingguIni > 0 && $dataMingguLalu === 0) {
            // Jika minggu lalu 0 tapi minggu ini ada kendaraan, anggap kenaikan 100%
            $persentase = 100;
        }

        // 7. Kembalikan data dalam bentuk Array (sesuai tipe StatistikMingguan di schema)
        return [
            'total' => $dataMingguIni,
            'persentase' => round($persentase, 1) // Dibulatkan 1 angka di belakang koma (contoh: 12.5)
        ];
    }
}