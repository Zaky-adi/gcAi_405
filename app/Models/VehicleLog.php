<?namespace App\Models;

use Device;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLog extends Model
{
    use HasFactory;

    // Menonaktifkan timestamps bawaan Laravel (created_at & updated_at) 
    // karena kita menggunakan 'detected_at' untuk optimasi database log
    public $timestamps = false;

    // Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'device_id',
        'confidence_score',
        'detected_at' // Tambahkan varian mobil di sini jika Anda menggunakan Skenario 2
    ];

    // Memastikan tipe data selalu konsisten saat ditarik dari database
    protected $casts = [
        'confidence_score' => 'float',
        'detected_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel Devices
     * Setiap log kendaraan pasti berasal dari satu kamera/device (Edge Device) tertentu
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}