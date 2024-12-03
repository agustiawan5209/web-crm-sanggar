<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        foreach ($months as $key => $value) {
            $data[] = Pembayaran::whereYear('created_at', '=', $tahun)->whereMonth('created_at', '=', $key)->whereIn('status', ['DITERIMA', 'SELESAI'])->sum('total');
        }
        return [
            'data' => $data,
            'label' => array_values(array_unique($months)),
        ];
    }
    public function calendar(Request $request)
    {
        $jadwal = Penyewaan::where('status','Dalam Penyewaan')->get();

        if ($request->exists('user_id')) {
            $user = User::with(['customer'])->find($request->user_id);
            $jadwal = Penyewaan::where('customer_id', $user->customer->id)->where('status','Dalam Penyewaan')->get();
        }
        $data = [];
        $tanggal = [];

        foreach ($jadwal as $key => $value) {
            $parsedDate = Carbon::parse($value->tgl_penyewaan)->format('Y-m-d'); // Menggunakan parse untuk string tanggal
            $data[] = [
                'tanggal' => $parsedDate,
                'deskripsi' => $value->customer_user['name'],
            ];
            $tanggal[] = $parsedDate;
        }

        return [
            'data' => $data,
            'tanggal' => array_values(array_unique($tanggal)),
        ];
    }
}
