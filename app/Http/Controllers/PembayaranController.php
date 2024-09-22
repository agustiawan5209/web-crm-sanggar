<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
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
        $columns[] = 'nama_customer';
        $columns[] = 'produk';
        $columns[] = 'jenis_bayar';
        $columns[] = 'tgl';
        $columns[] = 'total_transaksi';
        $columns[] = 'status';
        $columns[] = 'keterangan';

        return Inertia::render('Admin/Pembayaran/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Pembayaran::with(['penyewaan', 'penyewaan.customer', 'penyewaan.customer.user'])
            ->filterBySearch(Request::input('search'))
            ->filterOrderBy(Request::input('order'))
            ->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => true,
                'delete' => true,
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
        Request::validate(['slug'=> 'required|exists:pembayarans,id']);
        return Inertia::render('Admin/Pembayaran/Show', [
            'pembayaran'=> $pembayaran->with(['penyewaan', 'penyewaan.customer','penyewaan.customer.user'])->find(Request::input('slug')),
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
            'status'=> $request->status_bayar,
            'keterangan'=> $request->keterangan,
        ]);

        return redirect()->route('Penyewaan.show', ['slug'=> $request->slug])->with('message','Data Pembayaran Berhasil Di Update!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran = $pembayaran->find(Request::input('slug'));
        $penyewaan = Penyewaan::find($pembayaran->penyewaan_id);

        $penyewaan->delete();
        return redirect()->route('Pembayaran.index')->with('message','Data Pembayaran Berhasil Di Hapus!!');

    }
}
