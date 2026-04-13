<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingLot extends Model
{
    protected $fillable = ['device_id', 'area_name', 'total_capacity', 'current_occupied'];

    // Menghitung sisa slot secara otomatis: Capacity - Occupied
    protected $appends = ['available_slots'];

    public function getAvailableSlotsAttribute()
    {
        $slots = $this->total_capacity - $this->current_occupied;
        return $slots < 0 ? 0 : $slots; // Mencegah nilai minus jika ada error deteksi
    }
}