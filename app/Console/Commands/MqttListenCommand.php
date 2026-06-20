<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttListenCommand extends Command
{
    // Nama perintah yang akan dijalankan di terminal
    protected $signature = 'mqtt:listen';
    protected $description = 'Mendengarkan data deteksi kendaraan dari perangkat IoT via MQTT';

    public function handle()
    {
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = 'Laravel_Backend_Server_' . uniqid();
        $topic    = 'polibatam/gatecontrol/vehicle/logs';

        $this->info("Menghubungkan ke MQTT Broker ({$server})...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);

            // Konfigurasi koneksi (hilangkan jika butuh username/password)
            $connectionSettings = (new ConnectionSettings())
                ->setKeepAliveInterval(60);

            $mqtt->connect($connectionSettings, true);
            $this->info("[+] Berhasil terhubung! Mendengarkan di topik: {$topic}\n");

            // Berlangganan (Subscribe) ke topik
            $mqtt->subscribe($topic, function ($topic, $message) {
                $this->line("<fg=yellow>[!] Pesan masuk dari {$topic}</>");
                $this->line("Data: " . $message);

                // Ubah JSON menjadi Array PHP
                $data = json_decode($message, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // Masukkan data ke Firebase Firestore
                    $this->simpanKeFirestore($data);
                } else {
                    $this->error("Format pesan bukan JSON yang valid.");
                }
            }, 0);

            // Loop ini akan terus menahan terminal untuk mendengarkan pesan
            $mqtt->loop(true);
            $mqtt->disconnect();

        } catch (\Exception $e) {
            $this->error("Error MQTT: " . $e->getMessage());
        }
    }

    private function simpanKeFirestore(array $data)
    {
        try {
            // Karena ini command Laravel, kita bisa memanggil Firebase persis seperti di GraphQL
            $firestore = app('firebase.firestore')->database();
            $collection = $firestore->collection('vehicle_logs');

            // Format data yang akan di-insert
            $insertData = [
                'device_id' => $data['device_id'] ?? 'Unknown Device',
                'vehicle_type' => $data['vehicle_type'] ?? 'Unknown',
                'confidence_score' => $data['confidence_score'] ?? 0.0,
                // Menggunakan created_at sesuai skema database Anda
                'created_at' => $data['detected_at'] ?? now()->toIso8601String(),
            ];

            $collection->add($insertData);
            $this->info("<fg=green>[+] Data kendaraan ({$insertData['vehicle_type']}) berhasil disimpan ke Firebase!</>\n");

        } catch (\Exception $e) {
            $this->error("Gagal menyimpan ke Firebase: " . $e->getMessage());
        }
    }
}
