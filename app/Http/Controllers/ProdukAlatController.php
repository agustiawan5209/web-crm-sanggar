<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ProdukAlat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreProdukAlatRequest;
use App\Http\Requests\UpdateProdukAlatRequest;

class ProdukAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'produk_alats'; // Ganti dengan nama tabel yang Anda inginkan

        $columns[]= 'id';
        $columns[]= 'alat_galeri';
        $columns[]= 'nama';
        $columns[]= 'harga';
        $columns[]= 'harga_ongkir';
        $columns[]= 'stok';
        $columns[]= 'status';

        return Inertia::render('Admin/Alat/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'keterangan', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => ProdukAlat::filter(Request::only('search', 'order'))->paginate(10),
            'can' => [
                'add' => true,
                'edit' => true,
                'show' => true,
                'delete' => true,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Alat/Form', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukAlatRequest $request)
    {
        $produk = ProdukAlat::create($request->all());

        return redirect()->route('Produk.Alat.index')->with('message', 'Data Produk Alat Berhasil Di Tambah!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukAlat $produkAlat)
    {
        return Inertia::render('Admin/Alat/Show', [
            'alat'=> ProdukAlat::with(['image'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdukAlat $produkAlat)
    {
        return Inertia::render('Admin/Alat/Edit', [
            'alat'=> ProdukAlat::with(['image'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukAlatRequest $request, ProdukAlat $produkAlat)
    {
        $produk = ProdukAlat::find($request->slug)->update($request->all());

        return redirect()->route('Produk.Alat.index')->with('message', 'Data Produk Alat Berhasil Di Ubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdukAlat $produkAlat)
    {
        $produk = ProdukAlat::with(['image'])->find(Request::input('slug'));

        $gambar = new GambarController;
        foreach ($produk->image as $key => $value) {
            $gambar->destroy($value->id);
        }
        $produk->delete();

        return redirect()->route('Produk.Alat.index')->with('message', 'Data Produk Alat Berhasil Di Hapus!!');
    }

    public function updateStatus($status){
        $produk = ProdukAlat::find(Request::input('slug'));
        $produk->update(['status'=> $status]);

        return redirect()->route('Produk.Alat.index')->with('message', 'Data Status Produk Alat Berhasil Di Ubah!!');
    }
}
