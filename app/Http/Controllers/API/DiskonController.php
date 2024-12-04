<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use App\Models\GetDiskon;
use App\Models\KeepDiskon;
use App\Models\Penyewaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiskonController extends Controller
{
    public function get_diskon(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'jumlah' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($valid->fails()) {
            return response()->json('Error User Tidak DItemukan', 500);
        }
        $diskon = [];
        $user = User::with(['customer'])->find($request->user_id);

        $customer_id = $user->customer->id;
        if ($request->jumlah > 1) {
            $penyewaan = Penyewaan::where('customer_id', $customer_id)->whereMonth('created_at', Carbon::now()->format('m'))->get();
            $getdiskon = GetDiskon::where('min_quantity', '<=',$penyewaan->count())->get();
            foreach ($getdiskon as $key => $value) {
                $d = Diskon::find($value->diskon_id);
                if($d->jumlah >= 5){
                    $diskon[] = $d->jumlah;
                }
            }
        }else{
            $penyewaan = Penyewaan::where('customer_id', $customer_id)->get();
            $getdiskon = GetDiskon::where('min_quantity', '<=',$penyewaan->count())->get();
            foreach ($getdiskon as $key => $value) {
                $d = Diskon::find($value->diskon_id);
                if($d->jumlah >= 10){
                    $diskon[] = $d->jumlah;
                }
            }


            // $keepDiskon = KeepDiskon::where('min_frequency', '<', $penyewaan->count())->get();
            // foreach ($keepDiskon as $key => $value) {
            //     $diskon[] = Diskon::find($value->diskon_id)->jumlah;
            // }
        }
        return response()->json(count($diskon) > 0 ? end($diskon) : 0, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
