<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use App\Models\ProdukAlat;
use App\Models\ProdukJasa;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $penyewaan = Penyewaan::all();
        return Inertia::render('Dashboard', [
            'penyewaan'=> [
                'count'=> $penyewaan->count(),
            ],
            'produk'=> [
                'jasa'=> ProdukJasa::all()->count(),
                'alat'=> ProdukAlat::all()->count(),
            ],
            'transaksi'=> [
                'pendapatan'=> 'Rp.'. number_format(Pembayaran::where('status', 'DITERIMA')->sum('total'), 0,2),
            ]
        ]);

    }
}
