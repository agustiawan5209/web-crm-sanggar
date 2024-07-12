<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\GambarController;
use App\Http\Controllers\HomeController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



Route::middleware(['auth', 'verified', 'role:Admin'])->group(function () {


    // Router Pegawai
    Route::group(['prefix' => 'customer', 'as' => "Customer."], function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah-data-customer', 'create')->name('create');
            Route::get('/edit-data-customer', 'edit')->middleware(['auth', 'password.confirm'])->name('edit');
            Route::post('/store-data-customer', 'store')->name('store');
            Route::get('/detail-customer', 'show')->name('show');
            Route::put('/update-data-customer', 'update')->name('update');
            Route::delete('/hapus-data-customer', 'destroy')->name('destroy');

            // reset password

            Route::get('/reset-password-customer', 'resetpassword')->middleware(['auth', 'password.confirm'])->name('reset.password');
            Route::post('/reset-password-customer', 'resetpasswordUpdate')->name('reset.password');
        });
    });

    // Router Produk Alat
    Route::group(['prefix' => 'jasa', 'as' => "Produk.Jasa."], function () {
        Route::controller(ProdukJasaController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah-data-jasa', 'create')->name('create');
            Route::get('/edit-data-jasa', 'edit')   ->name('edit');
            Route::post('/store-data-jasa', 'store')->name('store');
            Route::get('/detail-jasa', 'show')->name('show');
            Route::put('/update-data-jasa', 'update')->name('update');
            Route::delete('/hapus-data-jasa', 'destroy')->name('destroy');
        });
    });
    // Router Produk Alat
    Route::group(['prefix' => 'alat', 'as' => "Produk.Alat."], function () {
        Route::controller(ProdukAlatController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah-data-alat', 'create')->name('create');
            Route::get('/edit-data-alat', 'edit')   ->name('edit');
            Route::post('/store-data-alat', 'store')->name('store');
            Route::get('/detail-alat', 'show')->name('show');
            Route::put('/update-data-alat', 'update')->name('update');
            Route::delete('/hapus-data-alat', 'destroy')->name('destroy');
        });
    });
    Route::group(['prefix' => 'galeri', 'as' => "Galeri."], function () {
        Route::controller(GambarController::class)->group(function () {
            Route::get('/alat', 'alat_index')->name('alat_index');
            Route::get('/jasa', 'jasa_index')->name('jasa_index');
            Route::post('/store_alat', 'store_alat')->name('alat_store');
            Route::post('/store_jasa', 'store_jasa')->name('jasa_store');
            Route::put('update/{id}', 'update')->name('edit');
            Route::delete('delete/{id}', 'destroy')->name('Delete');
        });
    });

    // Router Diskon
    Route::group(['prefix' => 'diskon', 'as' => "Diskon."], function () {
        Route::controller(DiskonController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah-data-diskon', 'create')->name('create');
            Route::get('/edit-data-diskon', 'edit')   ->name('edit');
            Route::post('/store-data-diskon', 'store')->name('store');
            Route::get('/detail-diskon', 'show')->name('show');
            Route::put('/update-data-diskon', 'update')->name('update');
            Route::delete('/hapus-data-diskon', 'destroy')->name('destroy');
        });
    });





});
