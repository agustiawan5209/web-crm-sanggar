<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Gambar;
use Illuminate\Support\Facades\Request;
class GambarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Galeri/Index', [
            'image' => Gambar::where('jasa_id', Request::input('slug'))->orWhere('alat_id', Request::input('slug'))->orderBy('status', 'desc')->get(),

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
    public function store()
    {
        $product_id = Request::input('jasa_id') ?? Request::input('alat_id');
        // $status = Request::input('status');
        $request = Request::validate(['file' => 'image|max:2000']);
        $photo = Request::file('file');
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo);

        if ($request) {
            $photo->storeAs('produk', $random_name_photo);
            // if($status){
            //     Gambar::where('status', true)->where('products_id', $product_id)->update(['status'=> false]);
            // }
            $galeri = Gambar::create([
                'jasa_id' => Request::input('jasa_id') ?? null,
                'alat_id' => Request::input('alat_id') ?? null,
                'image' => $random_name_photo,
                'status' => Request::input('status'),
            ]);
            return redirect()->route('Galeri.Index', ['products_id' => $product_id])->with('success', 'Berhasil Ditambah');
        } else {
            return redirect()->route('Galeri.Index', ['products_id' => $product_id])->with('errors', 'Foto Produk Gagal Di Tambah');
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
        if ($item) {
            $item->update(['is_default' => '1']);
            Gambar::whereNot('id', $id)->where('products_id', $item->products_id)->update(['is_default' => '0']);
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('success', 'Foto Produk Berhasil Di Jadikan Default');
        } else {
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('error', 'Foto Produk Gagal Di Jadikan Default');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Gambar::findOrFail($id);
        if ($item) {
            $item->delete();
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('success', 'Foto Produk Berhasil Di Hapus');
        } else {
            return redirect()->route('Galeri.Index', ['products_id' => $item->products_id])->with('error', 'Foto Produk Gagal Di Hapus');
        }
    }
}
