<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Diskon;
use App\Models\ProdukJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function welcome(){
        return Inertia::render('Welcome', [
            'jasa' => ProdukJasa::paginate(3),
            'hasLogin'=> Route::has('login'),
            'diskon'=> Diskon::with(['getdiskon', 'keepdiskon'])->get(),
        ]);
    }

}
