<?php
declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\VehicleLog; // Sesuaikan dengan nama model tabel log kendaraanmu
use Illuminate\Support\Carbon;

final class TotalKendaraanMasukHariIni
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): int
    {
        // Menghitung jumlah baris di mana kolom created_at adalah hari ini
        return VehicleLog::whereDate('created_at', Carbon::today())->count();
    }
}