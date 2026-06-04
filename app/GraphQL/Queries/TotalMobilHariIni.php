<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Carbon;

final readonly class TotalMobilHariIni
{
    public function __invoke($_, array $args): int
    {
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // Batas waktu hari ini
        $startOfDay = Carbon::today()->toIso8601String();
        $endOfDay = Carbon::tomorrow()->toIso8601String();

        // Ambil semua kendaraan HARI INI
        $documents = $collection
            ->where('created_at', '>=', $startOfDay)
            ->where('created_at', '<', $endOfDay)
            ->documents();

        // Hitung manual khusus 'mobil' via PHP untuk menghindari error composite index
        $totalMobil = 0;
        foreach ($documents as $doc) {
            $data = $doc->data();
            if (isset($data['vehicle_type']) && strtolower($data['vehicle_type']) === 'mobil') {
                $totalMobil++;
            }
        }

        return $totalMobil;
    }
}