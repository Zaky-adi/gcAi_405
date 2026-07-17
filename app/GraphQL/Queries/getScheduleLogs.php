<?php

namespace App\GraphQL\Queries;

use Exception; // Hapus use Google\Cloud\Core\Timestamp;

final class GetScheduleLogs
{
    public function __invoke($_, array $args)
    {
        try {
            // Kita gunakan app('firebase.firestore') untuk menghindari error Facade
            $firestore = app('firebase.firestore');
            $gateId = $args['gateId'];
            $limit = $args['limit'] ?? 5;

            // Tarik data langsung dari koleksi
            $documents = $firestore->database()->collection('schedule_logs')
                ->where('gate_id', '=', $gateId)
                ->documents();

            $logs = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();
                    
                    $logs[] = [
                        'id'        => $doc->id(),
                        'gateId'    => $data['gate_id'] ?? '',
                        'action'    => $data['action'] ?? '',
                        'details'   => $data['details'] ?? '',
                        // Langsung simpan string-nya tanpa perlu dikonversi
                        'createdAt' => $data['created_at'] ?? '' 
                    ];
                }
            }

            // Urutkan dari log terbaru ke terlama
            usort($logs, function($a, $b) {
                return strtotime($b['createdAt']) <=> strtotime($a['createdAt']);
            });

            // Kembalikan sesuai limit yang diminta frontend (misal: 5)
            return array_slice($logs, 0, $limit);

        } catch (Exception $e) {
            throw new Exception("Gagal memuat log: " . $e->getMessage());
        }
    }
}