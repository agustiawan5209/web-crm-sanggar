<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Gambar;
use App\Models\ProdukAlat;
use App\Models\ProdukJasa;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GambarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function alat_index()
    {
        $valid = Validator::make(Request::all(),[
            'slug' => 'required|exists:produk_alats,id',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Produk.Alat.index')->with('message', 'ID Data Gambar Tidak Ada');
        }
        return Inertia::render('Admin/Galeri/Index', [
            'image' => Gambar::where('alat_id', Request::input('slug'))->orderBy('status', 'desc')->get(),
            'produk'=> ProdukAlat::find(Request::input('slug')),
            'post_store'=> 'alat_store',

        ]);
    }
    public function jasa_index()
    {
        $valid = Validator::make(Request::all(),[
            'slug' => 'required|exists:produk_jasas,id',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Produk.Jasa.index')->with('message', 'ID Data Gambar Tidak Ada');
        }
        // dd(Gambar::find(1)->image_url);
        return Inertia::render('Admin/Galeri/Index', [
            'image' => Gambar::where('jasa_id', Request::input('slug'))->orderBy('status', 'desc')->get(),
            'produk'=> ProdukJasa::find(Request::input('slug')),
            'post_store'=> 'jasa_store',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Produk');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_alat()
    {
        $valid = Validator::make(Request::all(),[
            'produk_id' => 'required|exists:produk_alats,id',
            'file'=> 'required|image',
            'is_default'=> 'required',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Produk.Jasa.index')->with('message', $valid->errors()->first());
        }
        $product_id = Request::input('produk_id') ;
        $status = Request::input('is_default');
        $request = Request::validate(['file' => 'image|max:2000']);
        $photo = Request::file('file');
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo);

        if ($request) {
            $photo->storeAs('public/produk', $random_name_photo);
            if($status){
                Gambar::where('status', true)->where('alat_id', $product_id)->update(['status'=> false]);
            }
            $galeri = Gambar::create([
                'alat_id' => Request::input('produk_id'),
                'image' => $random_name_photo,
                'status' => Request::input('is_default'),
            ]);
            return redirect()->route('Galeri.alat_index', ['slug' => $product_id])->with('success', 'Berhasil Ditambah');
        } else {
            return redirect()->route('Galeri.alat_index', ['slug' => $product_id])->with('errors', 'Foto Produk Gagal Di Tambah');
        }
    }
    public function store_jasa()
    {
        $valid = Validator::make(Request::all(),[
            'produk_id' => 'required|exists:produk_jasas,id',
            'file'=> 'required|image',
            'is_default'=> 'required',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Produk.Jasa.index')->with('message', $valid->errors()->first());
        }
        $product_id = Request::input('produk_id') ;
        $status = Request::input('is_default');
        $request = Request::validate(['file' => 'image|max:2000']);
        $photo = Request::file('file');
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo);

        if ($request) {
            $photo->storeAs('public/produk', $random_name_photo);
            if($status){
                Gambar::where('status', true)->where('jasa_id', $product_id)->update(['status'=> false]);
            }
            $galeri = Gambar::create([
                'jasa_id' => Request::input('produk_id'),
                'image' => $random_name_photo,
                'status' => Request::input('is_default'),
            ]);
            return redirect()->route('Galeri.jasa_index', ['slug' => $product_id])->with('success', 'Berhasil Ditambah');
        } else {
            return redirect()->route('Galeri.jasa_index', ['slug' => $product_id])->with('errors', 'Foto Produk Gagal Di Tambah');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gambar $productGallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Gambar::findOrFail($id);
        dd($item);
        if ($item) {
            $item->update(['status' => true]);
            Gambar::whereNot('id', $id)->where('products_id', $item->products_id)->update(['status' => false]);
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('success', 'Foto Produk Berhasil Di Jadikan Default');
        } else {
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('error', 'Foto Produk Gagal Di Jadikan Default');
        }
    }
    public function update($id)
    {
        $item = Gambar::findOrFail($id);
        if ($item) {
            $item->update(['status' => '1']);
            return redirect()->back()->with('success', 'Foto Produk Berhasil Di Jadikan Default');;
        } else {
            return redirect()->back()->with('error', 'Foto Produk Gagal Di Jadikan Default');;
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Gambar::findOrFail($id);

        if (Storage::exists('public/produk/'. $item->image)){
            Storage::delete('public/produk/'. $item->image);
        }
        if ($item) {
            $item->delete();
            return redirect()->back()->with('success', 'Foto Produk Berhasil Di Hapus');
        } else {
            return redirect()->back()->with('error', 'Foto Produk Gagal Di Hapus');
        }
    }
}
