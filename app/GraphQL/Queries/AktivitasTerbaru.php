<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

final readonly class AktivitasTerbaru
{
    public function __invoke($_, array $args): array
    {
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // Mengambil 5 dokumen terbaru diurutkan dari yang paling baru
        $documents = $collection
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->documents();

        $results = [];
        
        foreach ($documents as $doc) {
            $data = $doc->data();
            
            // Mapping (mencocokkan) data Firestore dengan tipe 'VehicleLog' di schema.graphql
            $results[] = [
                'id' => $doc->id(), // Mengambil Document ID dari Firestore
                'device_id' => $data['device_id'] ?? 'Kamera Gerbang Utama',
                'vehicle_type' => $data['vehicle_type'] ?? 'Unknown',
                'confidence_score' => $data['confidence_score'] ?? 0.0,
                // Di schema kamu namanya detected_at, tapi di seeder kita pakai created_at
                // Jadi kita oper isi created_at ke detected_at
                'detected_at' => $data['created_at'] ?? null, 
            ];
        }

        return $results;
    }
}