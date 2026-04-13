<?php   
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parking_lots', function (Blueprint $table) {
            $table->id();
            // Menghubungkan area parkir dengan perangkat/kamera tertentu
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            
            $table->string('area_name'); // Contoh: Parkir Mobil Gedung Utama
            $table->integer('total_capacity'); // Kapasitas maksimal (misal: 50)
            $table->integer('current_occupied')->default(0); // Jumlah mobil yang sedang parkir
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parking_lots');
    }
};