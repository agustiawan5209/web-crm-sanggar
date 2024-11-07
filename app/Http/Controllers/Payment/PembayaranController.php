<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Models\ProdukAlat;
use App\Models\ProdukJasa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $valid = Validator::make(Request::all(), [
            'slug' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }
        return Inertia::render("Home/Penyewaan", [
            'produk' => ProdukJasa::with(['image'])->find(Request::input('slug')),
            'tipe' => 'jasa',
        ]);
    }
    /**
     * indexalat
     *
     * @return void
     */
    public function indexalat()
    {
        $valid = Validator::make(Request::all(), [
            'slug' => 'required',
            'quantity' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }
        return Inertia::render("Home/Penyewaan", [
            'produk' => ProdukAlat::with(['image'])->find(Request::input('slug')),
            'tipe' => 'alat',
            'quantity' => intval(Request::input('quantity')),
        ]);
    }
    /**
     * checkout
     *
     * @return void
     */
    public function checkout()
    {
        $valid = Validator::make(Request::all(), [
            'slug' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }
        $Produk = ProdukJasa::with(['image'])->find(Request::input('slug'));
        $penyewaan = Penyewaan::where('jenis','=', 'jasa')->where('status', '!=','Dalam Penyewaan')->get();

        return Inertia::render("Home/Checkout", [
            'produk' => $Produk,
            'tipe' => 'jasa',
            'sewa'=> $this->calendar('jasa'),
        ]);
    }
    /**
     * checkoutalat
     *
     * @return void
     */
    public function checkoutalat()
    {
        $valid = Validator::make(Request::all(), [
            'slug' => 'required',
            'quantity' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }
        return Inertia::render("Home/Checkout", [
            'produk' => ProdukAlat::with(['image'])->find(Request::input('slug')),
            'tipe' => 'alat',
            'quantity' => intval(Request::input('quantity')),
            'sewa'=> $this->calendar('alat'),

        ]);
    }

    public function success()
    {
        $request = Request::input('slug');
        $penyewaan = Penyewaan::with(['pembayaran'])->find($request);
        return Inertia::render("Home/Success", [
            'penyewaan' => $penyewaan,
        ]);
    }

    public function calendar($type)
    {
        $jadwal = Penyewaan::where('jenis','=', $type)->where('status','Dalam Penyewaan')->get();

        $data = [];
        $tanggal = [];

        foreach ($jadwal as $key => $value) {
            $parsedDate = Carbon::parse($value->created_at)->format('Y-m-d'); // Menggunakan parse untuk string tanggal
            $data[] = [
                'tanggal' => $parsedDate,
                'deskripsi' => "Penyewaan Sudah ada",
            ];
            $tanggal[] = $parsedDate;
        }

        return [
            'data' => $data,
            'tanggal' => array_values(array_unique($tanggal)),
        ];
    }
}
