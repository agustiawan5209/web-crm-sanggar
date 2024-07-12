<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Diskon;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreDiskonRequest;
use App\Http\Requests\UpdateDiskonRequest;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'diskons'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'nama';
        $columns[] = 'jenis';
        $columns[] = 'jumlah';
        $columns[] = 'kadaluarsa';

        return Inertia::render('Admin/Diskon/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Diskon::filter(Request::only('search', 'order'))->paginate(10),
            'can' => [
                'add' => true,
                'edit' => true,
                'show' => true,
                'delete' => true,
                'reset_password' => true,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Diskon/Form', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiskonRequest $request)
    {
        $diskon = Diskon::create($request->all());

        return redirect()->route('Diskon.index')->with('message', 'Data Diskon Berhasil Di tambah!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diskon $diskon)
    {
        return Inertia::render('Admin/Diskon/Show', [
            'diskon' => $diskon->find(Request::input('slug'))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diskon $diskon)
    {
        return Inertia::render('Admin/Diskon/Edit', [
            'diskon' => $diskon->find(Request::input('slug'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiskonRequest $request, Diskon $diskon)
    {
        $diskon = Diskon::find($request->slug)->update($request->all());

        return redirect()->route('Diskon.index')->with('message', 'Data Diskon Berhasil Di Ubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diskon $diskon)
    {
        $diskon= Diskon::find(Request::input('slug'));
        $diskon->delete();

        return redirect()->route('Diskon.index')->with('message', 'Data Diskon Berhasil Di Hapus!!');
    }
}
