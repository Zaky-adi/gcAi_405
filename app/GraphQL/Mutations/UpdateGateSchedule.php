<?php

namespace App\GraphQL\Mutations;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Exception;

final class UpdateGateSchedule
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $firestore = Firebase::firestore();
            $database = $firestore->database();
            
            $gateId = $args['id'];
            $now = gmdate('Y-m-d\TH:i:s\Z');
            
            // 1. Update Jadwal di Collection 'gate_schedules'
            $database->collection('gate_schedules')->document($gateId)->set([
                'start_time'  => $args['startTime'],
                'end_time'    => $args['endTime'],
                'active_days' => $args['activeDays'],
                'updated_at'  => $now
            ], ['merge' => true]); // merge true agar data lain tidak tertimpa

            // 2. Catat Log ke Collection 'schedule_logs'
            $daysString = implode(', ', $args['activeDays']);
            $database->collection('schedule_logs')->add([
                'gate_id'    => $gateId,
                'action'     => 'Jadwal diperbarui',
                'details'    => $args['startTime'] . ' - ' . $args['endTime'] . ' (' . $daysString . ')',
                'created_at' => $now
            ]);

            // 3. Return data sesuai struktur GraphQL Mutation
            return [
                'id' => $gateId,
                'startTime' => $args['startTime'],
                'endTime' => $args['endTime'],
            ];

        } catch (Exception $e) {
            // Melempar error agar ditangkap oleh GraphQL
            throw new Exception("Gagal update ke Firebase: " . $e->getMessage());
        }
    }
}