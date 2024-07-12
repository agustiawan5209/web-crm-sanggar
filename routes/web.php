<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\GambarController;
use App\Http\Controllers\Home\ProdukController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Payment\PembayaranController;
use App\Http\Controllers\ProdukAlatController;
use App\Http\Controllers\ProdukJasaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'welcome'])->name('home');

Route::controller(ProdukController::class)->group(function () {
    Route::get('/semua-jasa', 'produk_jasa')->name('all.produk_jasa');
    Route::get('/semua-alat', 'produk_alat')->name('all.produk_alat');
});

Route::middleware(['auth', 'verified', 'role:Customer'])->group(function () {
    Route::controller(PembayaranController::class)->group(function () {
        Route::get('/checkout-penyewaan', 'index')->name('payment.checkout');
    });
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
