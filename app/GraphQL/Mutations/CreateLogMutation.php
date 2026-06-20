<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Carbon;
use Exception;
use GraphQL\Error\Error;

class CreateLogMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function resolve($_, array $args)
    {
        try {
            // 1. Hubungkan ke database Firebase Firestore
            $firestore = app('firebase.firestore')->database();
            
            // 2. Pilih nama tabel/koleksi
            $collection = $firestore->collection('vehicle_logs');

            // 3. Susun data yang akan disimpan (ditambah waktu saat ini)
            $data = [
                'device_id'        => $args['device_id'],
                'vehicle_type'     => $args['vehicle_type'],
                'confidence_score' => $args['confidence_score'],
                'created_at'       => Carbon::now()->toIso8601String(), // Format waktu: 2026-06-21T10:30:00+07:00
            ];

            // 4. Simpan data ke Firebase
            $documentReference = $collection->add($data);

            // 5. Kembalikan respons sukses ke Python / Web sesuai format GraphQL
            return [
                'id'               => $documentReference->id(),
                'device_id'        => $data['device_id'],
                'vehicle_type'     => $data['vehicle_type'],
                'confidence_score' => $data['confidence_score'],
                'detected_at'      => $data['created_at'],
            ];

        } catch (Exception $e) {
            // Jika gagal menyimpan ke Firebase, lemparkan error aslinya agar ketahuan
            throw new Error("Gagal menyimpan ke Firebase: " . $e->getMessage());
        }
    }
}