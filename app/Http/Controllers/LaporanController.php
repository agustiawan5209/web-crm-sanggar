<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Request;
use PDF;

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
        $columns[] = 'total_bayar';

        $penyewaaan = Penyewaan::with(['customer', 'customer.user'])->where('status', "SELESAI")
        ->where('jenis', 'alat')
        ->when(Request::input('start_date') != null && Request::input('end_date') != null, function ($query) {
            $query->whereBetween('created_at', [Request::input('start_date'), Request::input('end_date')]);
        })
        ->paginate(10);
        $transaksi = [];
        foreach($penyewaaan->items() as $key=> $value ){
            $transaksi[$key] = $value->pembayaran->total;
        }
        return Inertia::render('Admin/Laporan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => $penyewaaan,
            'total_pendapatan' => 'Rp.'. number_format(array_sum($transaksi),0,2),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ],
            'tipe' => 'alat',
            'datelaporan' => Request::only('start_date', 'end_date'),
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
        $columns[] = 'jumlah';
        $columns[] = 'tgl_pengambilan';
        $columns[] = 'tgl_pengembalian';
        $columns[] = 'status';
        $columns[] = 'total_bayar';

        $penyewaaan = Penyewaan::with(['customer', 'customer.user'])->where('status', "SELESAI")
        ->where('jenis', 'jasa')
        ->when(Request::input('start_date') != null && Request::input('end_date') != null, function ($query) {
            $query->whereBetween('created_at', [Request::input('start_date'), Request::input('end_date')]);
        })
        ->paginate(10);

        $transaksi = [];
        foreach($penyewaaan->items() as $key=> $value ){
            $transaksi[$key] = $value->pembayaran->total;
        }
        return Inertia::render('Admin/Laporan/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => $penyewaaan,
            'total_pendapatan' => 'Rp.'. number_format(array_sum($transaksi),0,2),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ],
            'tipe' => 'jasa',
            'datelaporan' => Request::only('start_date', 'end_date'),
        ]);
    }

    public function cetakPDF()
    {
        // Ambil data penyewaan berdasarkan id
        $data = Penyewaan::where('jenis', Request::input('type'))
            ->whereBetween('created_at', Request::only('start_date', 'end_date'))
            ->get();

        // Definisikan kolom yang akan ditampilkan di PDF
        $columns = [
            'kode_transaksi',
            'customer_id',
            'jenis',
            'produk',
            'tgl_pengambilan',
            'tgl_pengembalian',
            'status'
        ];

        // Load view untuk PDF dan pass data penyewaan
        $pdf = PDF::loadView('pdf.penyewaan', compact('data', 'columns'));

        // Unduh PDF
        return $pdf->download('penyewaan.pdf');
    }
}
