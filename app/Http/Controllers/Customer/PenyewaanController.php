<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PenyewaanController extends Controller
{
    public function index()
    {
        $tableName = 'penyewaans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'customer_id';
        $columns[] = 'produk';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';

        $user = User::with('customer')->find(Auth::user()->id);
        return Inertia::render('User/Penyewaan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user', 'review', 'pembayaran'])
                ->filter(Request::only('search', 'order'))
                ->where('customer_id', $user->customer->id)
                ->orderBy('id', 'desc')
                ->where('status', '!=', 'SELESAI')

                ->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
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
        $columns[] = 'produk';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';
        $user = User::with(['customer'])->find(Auth::user()->id);


        return Inertia::render('User/Penyewaan/Riwayat', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user', 'review','pembayaran'])
                ->orderBy('id', 'desc')
                ->where('customer_id', $user->customer->id)
                ->where('status', 'SELESAI')
                ->get(),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ]
        ]);
    }

    public function updatePayLater(Penyewaan $penyewaan)
    {
        $request = Request::validate([
            'slug'=>'required|exists:penyewaans,id',
            'bukti'=> 'required|image',
        ]);
        $photo = Request::file('bukti');
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo);
        $request = Request::all();
        $penyewaan = Penyewaan::find(Request::input('slug'));
        $pembayaran = Pembayaran::find($penyewaan->pembayaran->id);
        // dd($pembayaran);
        $photo->storeAs('public/bukti_bayar', $random_name_photo);

        $pembayaran->update([
            'bukti' => $random_name_photo,
            'jenis_bayar' => Request::input('jenis_bayar'),
            'total'=> Request::input('jumlah_bayar'),
            'sub_total'=> Request::input('total'),
            'tgl'=> Request::input('tgl'),
            'status' => "PENDING",
        ]);

        return redirect()->route('Customer.Pembayaran.show', ['slug' => $pembayaran->id])->with('message', 'Data Penyewaan Berhasil Di Update!!');
    }
}
