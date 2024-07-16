<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Penyewaan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PenyewaanController extends Controller
{
    public function index(){
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
            'data' => Penyewaan::with(['customer', 'customer.user','review'])->filter(Request::only('search', 'order'))->where('customer_id', $user->customer->id)->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ]
        ]);
    }
}
