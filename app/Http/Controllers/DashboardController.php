<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleLog;
use App\Models\ParkingLot;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil total mobil masuk per hari ini
        $totalMobilHariIni = VehicleLog::whereDate('detected_at', today())->count();

        // 2. Mengambil 10 log kendaraan terakhir beserta nama kameranya
        $logTerbaru = VehicleLog::with('device:id,device_name,location')
                                ->orderBy('detected_at', 'desc')
                                ->limit(10)
                                ->get();

        // 3. Mengambil rata-rata akurasi AI (Confidence Score) hari ini
        // Gunakan round() agar angkanya rapi, misal: 0.95
        $rataRataAkurasi = round(VehicleLog::whereDate('detected_at', today())->avg('confidence_score'), 2);

        // 4. (Tambahan) Mengambil data sisa parkir untuk ditampilkan
        $statusParkir = ParkingLot::all();

        // Mengirimkan data-data tersebut ke file tampilan (Blade View)
        return view('dashboard.index', compact(
            'totalMobilHariIni', 
            'logTerbaru', 
            'rataRataAkurasi',
            'statusParkir'
        ));
    }
}