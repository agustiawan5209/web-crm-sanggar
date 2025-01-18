<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\PembayaranController as CustomerPembayaranController;
use App\Http\Controllers\Customer\PenyewaanController as CustomerPenyewaanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PembayaranController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\ReviewController;

Route::middleware(['auth', 'verified', 'role:Customer', 'throttle:10,1'])->group(function () {
    // Dashboard User
    Route::group(['prefix' => 'user', 'as' => 'Customer.'], function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/profile', 'profile')->name('profile');
        });
        // Penyewaan Jasa Barang
        Route::controller(CustomerPenyewaanController::class)->group(function () {
            Route::get('/penyewaan', 'index')->name('penyewaan');
            Route::get('/riwayat-penyewaan', 'riwayat')->name('riwayat.penyewaan');
            Route::post('/update-penyewaan', 'updatePayLater')->name('penyewaan.store.pay.later');
        });


        // Data Pembayaran User
        Route::group(['prefix' => 'pembayaran', 'as' => "Pembayaran."], function () {
            Route::controller(CustomerPembayaranController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/detail-pembayaran', 'show')->name('show');
            });
        });
    });
    Route::group(['prefix' => 'review', 'as' => 'Review.'], function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::post('/store', 'store')->name('store');
        });
    });
    // End Dashboard
    // Pembayaran COntroller
    Route::controller(PembayaranController::class)->group(function () {
        Route::get('/checkout-penyewaan-paket', 'checkout')->name('payment.checkout');
        Route::get('/checkout-penyewaan-kostum', 'checkoutalat')->name('payment.checkout.alat');
        Route::get('/success-penyewaan', 'success')->name('payment.success');
    });


    // Buat Data Penyewaan
    Route::post('store-penyewaan', [PenyewaanController::class, 'store'])->name('Penyewaan.Store');
    Route::post('store-penyewaan-later', [PenyewaanController::class, 'storepaylater'])->name('Penyewaan.Store.later');
});

Route::controller(PembayaranController::class)->group(function () {
    Route::get('/list-penyewaan-paket', 'index')->name('payment.list');
    Route::get('/list-penyewaan-kostum', 'indexalat')->name('payment.list.alat');
    Route::get('/success-penyewaan', 'success')->name('payment.success');
});


