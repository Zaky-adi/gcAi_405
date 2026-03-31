<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->string('vehicle_type'); // Contoh: Motor, Mobil Pribadi, Bus
            $table->float('confidence_score'); // Tingkat akurasi deteksi AI
            $table->timestamp('detected_at')->useCurrent();
            // Indexing sangat penting di sini untuk mempercepat filter statistik di dashboard
            $table->index('detected_at'); 
            $table->index('vehicle_type');
        });
    }
};