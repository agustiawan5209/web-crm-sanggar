<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function transaksi(Request $request, $tahun = '2024')
    {
         // Mendapatkan tanggal saat ini
         $now = Carbon::now();

         // Mendapatkan 12 bulan terakhir dari sekarang
         $last12Months = [];
         for ($i = 0; $i < 12; $i++) {
             $last12Months[] = $now->copy()->subMonths($i);
         }
         $data = [];
         $months = [
             '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
         ];
         foreach ($months as $key => $value) {
             $data[] = Pembayaran::whereYear('created_at', '=', $tahun)->whereMonth('created_at', '=', $key)->where('status','DITERIMA')->sum('total');
         }
         return [
             'data' => $data,
             'label' => array_values(array_unique($months)),
         ];
    }
}
