<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name');
            $table->string('location'); // Contoh: Portal Gerbang Utama
            $table->text('ip_address')->nullable(); // SENSITIF: Harus dienkripsi
            $table->text('rtsp_url')->nullable(); // SENSITIF: URL stream kamera
            $table->string('mac_address')->unique(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};