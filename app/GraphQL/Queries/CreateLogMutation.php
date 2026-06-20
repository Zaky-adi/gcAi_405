<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Carbon;

class CreateLogMutation
{
    public function resolve($_, array $args)
    {
        try {

        dd(env('FIREBASE_PROJECT_ID'));

        $firestore = app('firebase.firestore')->database();

        $collection = $firestore->collection('vehicle_logs');

        $data = [
            'device_id' => $args['device_id'],
            'vehicle_type' => $args['vehicle_type'],
            'confidence_score' => $args['confidence_score'],
            'created_at' => Carbon::now()->toIso8601String(),
        ];

        $documentReference = $collection->add($data);

        return [
            'id' => $documentReference->id(),
            'device_id' => $data['device_id'],
            'vehicle_type' => $data['vehicle_type'],
            'confidence_score' => $data['confidence_score'],
            'detected_at' => $data['created_at'],
        ];

    } catch (\Throwable $e) {

        throw new \Exception(
            $e->getMessage()
        );
    }

        // 1. Hubungkan ke Firestore
        $firestore = app('firebase.firestore')->database();
        $collection = $firestore->collection('vehicle_logs');

        // 2. Siapkan data yang akan disimpan
        $data = [
            'device_id' => $args['device_id'],
            'vehicle_type' => $args['vehicle_type'],
            'confidence_score' => $args['confidence_score'],
            'created_at' => Carbon::now()->toIso8601String(), // Waktu saat ini
        ];

        // 3. Simpan ke Firebase dan ambil ID dokumen barunya
        $documentReference = $collection->add($data);

        // 4. Kembalikan respons ke Python/Frontend sesuai format VehicleLog di schema
        return [
            'id' => $documentReference->id(),
            'device_id' => $data['device_id'],
            'vehicle_type' => $data['vehicle_type'],
            'confidence_score' => $data['confidence_score'],
            'detected_at' => $data['created_at'],
        ];
    }
}
