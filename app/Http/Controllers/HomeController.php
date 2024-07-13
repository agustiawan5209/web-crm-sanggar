<?php

namespace App\Http\Controllers;

use App\Models\ProdukJasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function welcome(){
        return Inertia::render('Welcome', [
            'jasa' => ProdukJasa::paginate(3),
            'hasLogin'=> Route::has('login'),
        ]);
    }

}
