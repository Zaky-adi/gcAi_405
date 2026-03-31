<?php

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_name', 'location', 'ip_address', 'rtsp_url', 'mac_address', 'is_active'];

    // Enkripsi otomatis saat masuk database, dekripsi otomatis saat dipanggil
    protected $casts = [
        'ip_address' => 'encrypted',
        'rtsp_url' => 'encrypted',
    ];
}