<?php

namespace App\Http\Controllers\Payment;

use Inertia\Inertia;
use App\Models\ProdukJasa;
use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\ProdukAlat;
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
        return Inertia::render("Home/Checkout", [
            'produk' => ProdukJasa::with(['image'])->find(Request::input('slug')),
            'tipe' => 'jasa',
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
}
