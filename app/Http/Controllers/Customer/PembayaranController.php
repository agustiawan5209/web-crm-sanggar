<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'pembayarans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'produk';
        $columns[] = 'jenis_bayar';
        $columns[] = 'tgl';
        $columns[] = 'total_transaksi';
        $columns[] = 'status';
        $columns[] = 'keterangan';
        $user = User::find(Auth::user()->id);
        return Inertia::render('User/Pembayaran/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Pembayaran::whereHas('penyewaan', function ($query) use ($user) {
                $query->where('customer_id', $user->customer->id);
            })
            ->orderBy('id','desc')
            ->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => true,
                'delete' => false,
                'reset_password' => false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePembayaranRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        Request::validate(['slug' => 'required|exists:pembayarans,id']);
        return Inertia::render('User/Pembayaran/Show', [
            'pembayaran' => $pembayaran->with(['penyewaan', 'penyewaan.customer', 'penyewaan.customer.user'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePembayaranRequest $request, Pembayaran $pembayaran)
    {
        $pembayaran = Pembayaran::find($request->slug);
        $pembayaran->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'total'=> Request::input('jumlah_bayar'),
            'sub_total'=> Request::input('total'),
        ]);

        return redirect()->route('Pembayaran.show', ['slug' => $request->slug])->with('message', 'Data Pembayaran Berhasil Di Update!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        //
    }
}
