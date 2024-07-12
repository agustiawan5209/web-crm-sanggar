<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ProdukJasa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function produk_jasa(Request $request){
        return Inertia::render('Home/Produk',[
            'produk'=> ProdukJasa::paginate(10),
            'tipe'=> 'jasa',
        ]);
    }
    public function produk_alat(Request $request){
        return Inertia::render([
            'produk'=> ProdukJasa::paginate(10),
            'tipe'=> 'jasa',
        ]);
    }
}
