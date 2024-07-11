<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ProdukJasa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreProdukJasaRequest;
use App\Http\Requests\UpdateProdukJasaRequest;

class ProdukJasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'produk_jasas'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[]= 'id';
        $columns[]= 'jasa_galeri';
        $columns[]= 'nama';
        $columns[]= 'harga';
        $columns[]= 'status';

        return Inertia::render('Admin/Jasa/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'keterangan', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => ProdukJasa::filter(Request::only('search', 'order'))->paginate(10),
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
        return Inertia::render('Admin/Jasa/Form', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukJasaRequest $request)
    {
        $produk = ProdukJasa::create($request->all());

        return redirect()->route('Produk.Jasa.index')->with('message', 'Data Produk Jasa Berhasil Di Tambah!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukJasa $produkJasa)
    {
        return Inertia::render('Admin/Jasa/Show', [
            'jasa'=> ProdukJasa::with(['image'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdukJasa $produkJasa)
    {
        return Inertia::render('Admin/Jasa/Edit', [
            'jasa'=> ProdukJasa::with(['image'])->find(Request::input('slug')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukJasaRequest $request, ProdukJasa $produkJasa)
    {
        $produk = ProdukJasa::find($request->slug)->update($request->all());

        return redirect()->route('Produk.Jasa.index')->with('message', 'Data Produk Jasa Berhasil Di Ubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdukJasa $produkJasa)
    {
        $produk = ProdukJasa::find(Request::input('slug'));
        $produk->delete();

        return redirect()->route('Produk.Jasa.index')->with('message', 'Data Produk Jasa Berhasil Di Hapus!!');
    }

    public function updateStatus($status){
        $produk = ProdukJasa::find(Request::input('slug'));
        $produk->update(['status'=> $status]);

        return redirect()->route('Produk.Jasa.index')->with('message', 'Data Status Produk Jasa Berhasil Di Ubah!!');
    }
}
