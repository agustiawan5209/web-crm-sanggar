<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StorePenyewaanRequest;
use App\Http\Requests\UpdatePenyewaanRequest;
use App\Http\Requests\PaylaterPenyewaanRequest;

class PenyewaanController extends Controller
{
    public function success()
    {
        return Inertia::render("Home/Success", []);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'penyewaans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'customer_id';
        $columns[] = 'jenis';
        $columns[] = 'produk';
        $columns[] = 'jumlah';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';

        return Inertia::render('Admin/Penyewaan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user', 'pembayaran'])->filter(Request::only('search', 'order'))->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => true,
                'delete' => false,
                'reset_password' => false,
            ]
        ]);
    }
    public function riwayat()
    {
        $tableName = 'penyewaans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'customer_id';
        $columns[] = 'jenis';
        $columns[] = 'produk';
        $columns[] = 'jumlah';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        // $columns[] = 'status';

        return Inertia::render('Admin/Penyewaan/Riwayat', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user'])
                ->filter(Request::only('search', 'order'))
                ->where('status', 'SELESAI')
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

    private function generateKodeTransaksi()
    {
        // Mendapatkan ID terakhir
        $lastId = Pembayaran::max('id') + 1;

        // Membuat kode transaksi dengan format yang diinginkan
        return 'TRX-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storepaylater(PaylaterPenyewaanRequest $request)
    {
        // dd($request->quantity == null ? 1 : $request->quantity);
        $user = User::with(['customer'])->find(Auth::user()->id);
        $penyewaan = Penyewaan::create([
            'customer_id' => $user->customer->id,
            'customer_user' => $user,
            'jenis' => $request->jenis,
            'produk_id' => $request->produk,
            'produk' => $request->produk['nama'],
            'jumlah'=> $request->quantity == null ? 1 : $request->quantity,
            'tgl_pengambilan' => $request->tgl_pengambilan,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'status' => "Dalam Penyewaan",
            'tipe_bayar'=> 'Bayar Nanti',
        ]);
        Pembayaran::create([
            'kode_transaksi' => $this->generateKodeTransaksi(),
            'penyewaan_id' => $penyewaan->id,
            'total' => $request->jumlah_bayar,
            'jenis_bayar' => 'Bayar Nanti',

            'status' => "PENDING",
        ]);

        return redirect()->route('payment.success');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenyewaanRequest $request)
    {
        // dd(json_encode($request->produk));
        $user = User::with(['customer'])->find(Auth::user()->id);
        $penyewaan = Penyewaan::create([
            'customer_id' => $user->customer->id,
            'customer_user' => $user,
            'jenis' => $request->jenis,
            'produk_id' => $request->produk,
            'produk' => $request->produk['nama'],
            'jumlah'=> $request->quantity == null ? 1 : $request->quantity,
            'tgl_pengambilan' => $request->tgl_pengambilan,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'status' => "Dalam Penyewaan",
        ]);
        $photo = $request->bukti;
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo);

        $photo->storeAs('public/bukti_bayar', $random_name_photo);
        Pembayaran::create([
            'kode_transaksi' => $this->generateKodeTransaksi(),
            'bukti' => $random_name_photo,
            'penyewaan_id' => $penyewaan->id,
            'total' => $request->jumlah_bayar,
            'jenis_bayar' => $request->jenis_bayar,
            'tgl' => $request->tgl_pembayaran,
            'status' => "PENDING",
        ]);

        return redirect()->route('payment.success');
    }

    /**
     * Display the specified resource.
     */
    public function riwayat_show(Penyewaan $penyewaan)
    {
        Request::validate(['slug' => 'required|exists:penyewaans,id']);
        return Inertia::render('Admin/Penyewaan/Show', [
            'penyewaan' => $penyewaan->with(['customer', 'customer.user', 'pembayaran'])->find(Request::input('slug')),
        ]);
    }
    public function show(Penyewaan $penyewaan)
    {
        Request::validate(['slug' => 'required|exists:penyewaans,id']);
        return Inertia::render('Admin/Penyewaan/Show', [
            'penyewaan' => $penyewaan->with(['customer', 'customer.user', 'pembayaran'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewaan $penyewaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenyewaanRequest $request, Penyewaan $penyewaan)
    {
        // dd($request->all());
        $penyewaan = Penyewaan::find($request->slug);
        $penyewaan->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'tgl_pengembalian' => $request->tgl_pengembalian,
        ]);

        return redirect()->route('Penyewaan.show', ['slug' => $request->slug])->with('message', 'Data Penyewaan Berhasil Di Update!!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewaan $penyewaan)
    {
        //
    }
}
