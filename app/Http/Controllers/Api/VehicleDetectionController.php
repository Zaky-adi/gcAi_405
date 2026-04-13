<?php   
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleLog;
use App\Models\ParkingLot;
use Illuminate\Support\Facades\DB;

class VehicleDetectionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data yang dikirim oleh Edge Device / Raspberry Pi
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'parking_lot_id' => 'required|exists:parking_lots,id',
            'confidence_score' => 'required|numeric',
            'direction' => 'required|in:in,out' // Memberi tahu apakah mobil masuk atau keluar
        ]);

        try {
            // Memulai Database Transaction
            // Jika salah satu proses gagal, semua akan dibatalkan (rollback)
            DB::beginTransaction();

            // 2. Simpan data mobil ke tabel vehicle_logs
            VehicleLog::create([
                'device_id' => $request->device_id,
                'confidence_score' => $request->confidence_score,
            ]);

            // 3. DI SINILAH KODE ANDA DILETAKKAN
            // Update jumlah parkir berdasarkan arah mobil
            if ($request->direction === 'in') {
                // Jika masuk, tambah jumlah kendaraan
                ParkingLot::where('id', $request->parking_lot_id)->increment('current_occupied');
                
            } elseif ($request->direction === 'out') {
                // Jika keluar, kurangi jumlah kendaraan
                // Tambahkan kondisi where agar nilai tidak pernah minus (jika sistem restart/error)
                ParkingLot::where('id', $request->parking_lot_id)
                          ->where('current_occupied', '>', 0)
                          ->decrement('current_occupied');
            }

            // Simpan semua perubahan secara permanen ke database
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Kendaraan terdeteksi dan kapasitas parkir diperbarui.'
            ], 201);

        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan database ke kondisi semula
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data sistem.'
            ], 500);
        }
    }
}