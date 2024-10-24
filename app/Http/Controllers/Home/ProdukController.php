<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ProdukAlat;
use App\Models\ProdukJasa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function produk_jasa(Request $request)
    {
        return Inertia::render('Home/Produk', [
            'produk' => ProdukJasa::with(['image', 'review'])->paginate($request->p),
            'tipe' => 'jasa',
            'p'=> $request->p == null ? 10: $request->p,
        ]);
    }
    public function produk_alat(Request $request)
    {
        return Inertia::render('Home/Produk', [
            'produk' => ProdukAlat::with(['image', 'review'])->paginate($request->p),
            'tipe' => 'alat',
            'p'=> $request->p == null ? 10: $request->p,
        ]);
    }

    public function produk_detail(Request $request, $tipe, $slug)
    {
        if ($tipe == 'jasa') {
            $produk = ProdukJasa::with(['image', 'review'])->findOrFail($slug);
        }
        else if ($tipe == 'alat') {
            $produk = ProdukAlat::with(['image', 'review'])->findOrFail($slug);
        } else {
            return redirect()->back();
        }
        return Inertia::render('Home/DetailProduk', [
            'produk' => $produk,
            'tipe' => $tipe,
        ]);
    }
}
