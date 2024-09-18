<?php

use App\Http\Controllers\API\DiskonController;
use App\Http\Controllers\API\GrafikController;
use App\Http\Controllers\InformationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=> 'diskon', 'as'=> 'Api.diskon.'], function () {
    Route::controller(DiskonController::class)->group(function(){
        Route::get('get','get_diskon')->name('get_diskon');
        Route::get('keep','keep_diskon')->name('keep_diskon');
    });
});

Route::get('transaksi', [GrafikController::class, 'transaksi'])->name('grafik.transaksi');
Route::get('calendar', [GrafikController::class, 'calendar'])->name('grafik.calendar');

Route::get('all-banner', [InformationController::class, 'getAllData'])->name('information.all.data');

