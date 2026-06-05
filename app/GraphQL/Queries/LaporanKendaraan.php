<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final readonly class LaporanKendaraan
{
    public function __invoke($_, array $args): array
    {
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // 1. Parsing Tanggal dari Input Frontend (Pastikan frontend mengirim format YYYY-MM-DD)
        // Kita set startDate dari jam 00:00:00 dan endDate sampai jam 23:59:59
        $startDate = Carbon::parse($args['startDate'])->startOfDay();
        $endDate = Carbon::parse($args['endDate'])->endOfDay();

        // 2. Tarik data dari Firestore berdasarkan rentang waktu
        $documents = $collection
            ->where('created_at', '>=', $startDate->toIso8601String())
            ->where('created_at', '<=', $endDate->toIso8601String())
            ->documents();

        $filteredData = [];
        $hourCounts = []; // Untuk menghitung puncak tertinggi per jam
        $filterType = isset($args['vehicleType']) ? strtolower($args['vehicleType']) : null;

        // 3. Looping dan Filtering Data
        foreach ($documents as $doc) {
            $data = $doc->data();
            $vType = strtolower($data['vehicle_type'] ?? '');

            // Jika user memilih jenis kendaraan tertentu (bukan semua), lewati yang tidak cocok
            if ($filterType && $filterType !== 'semua' && $filterType !== $vType) {
                continue; 
            }

            $createdAt = $data['created_at'] ?? Carbon::now()->toIso8601String();
            
            // Catat jam untuk mencari puncak tertinggi (Format: H:00)
            $jam = Carbon::parse($createdAt)->format('H:00');
            $hourCounts[$jam] = ($hourCounts[$jam] ?? 0) + 1;

            // Masukkan ke array data tabel
            $filteredData[] = [
                'id' => $doc->id(),
                'device_id' => $data['device_id'] ?? 'IoT Device',
                'vehicle_type' => $data['vehicle_type'] ?? 'Unknown',
                'confidence_score' => $data['confidence_score'] ?? 0.0,
                'detected_at' => $createdAt,
            ];
        }

        // 4. Hitung Statistik (Ringkasan)
        $totalData = count($filteredData);

        // A. Menghitung Rata-rata per hari
        // Ditambah 1 agar jika start dan end di hari yang sama, pembaginya adalah 1 hari
        $totalDays = $startDate->diffInDays($endDate) + 1; 
        $rataRata = $totalData > 0 ? ($totalData / $totalDays) : 0;

        // B. Mencari Puncak Tertinggi
        $maxJam = '-';
        $maxJumlah = 0;
        foreach ($hourCounts as $jam => $jumlah) {
            if ($jumlah > $maxJumlah) {
                $maxJumlah = $jumlah;
                $maxJam = $jam;
            }
        }

        // 5. Kembalikan data sesuai dengan schema GraphQL ReportLaporan
        return [
            'ringkasan' => [
                'total' => $totalData,
                'rataRataPerHari' => round($rataRata, 1),
                'puncakTertinggi' => [
                    'jam' => $maxJam,
                    'jumlah' => $maxJumlah
                ]
            ],
            'data' => $filteredData
        ];
    }
}