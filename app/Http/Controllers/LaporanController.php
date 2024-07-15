<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Request;

class LaporanController extends Controller
{
    public function laporan_alat(Request $request)
    {
        $tableName = 'penyewaans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'customer_id';
        $columns[] = 'jenis';
        $columns[] = 'produk';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';

        return Inertia::render('Admin/Laporan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user'])->where('status', "SELESAI")
                ->where('jenis', 'alat')
                ->whereBetween('created_at', [Request::input('start_date'), Request::input('end_date')])->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ],
            'tipe' => 'alat',
        ]);
    }
    public function laporan_jasa(Request $request)
    {
        $tableName = 'penyewaans'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'kode_transaksi';
        $columns[] = 'customer_id';
        $columns[] = 'jenis';
        $columns[] = 'produk';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';

        return Inertia::render('Admin/Laporan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Penyewaan::with(['customer', 'customer.user'])->where('status', "SELESAI")
                ->where('jenis', 'jasa')
                ->whereBetween('created_at', Request::only('start_date', 'end_date'))
                ->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ],
            'tipe' => 'jasa',
        ]);
    }
}
