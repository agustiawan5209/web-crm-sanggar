<?php


namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Information;
use App\Models\GetInformation;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreInformationRequest;
use App\Http\Requests\UpdateInformationRequest;
use App\Models\KeepInformation;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'informations'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'gambar';
        $columns[] = 'title';
        $columns[] = 'description';
        // $columns[] = 'kadaluarsa';

        return Inertia::render('Admin/Information/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Information::filter(Request::only('search', 'order'))->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => true,
                'reset_password' => false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Information/Form', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInformationRequest $request)
    {
        $photo = Request::file('image');
        $name_photo = $photo->getClientOriginalName();
        $random_name_photo = md5($name_photo) . '.' . $photo->getClientOriginalExtension();
        $data = $request->all();
        if ($request) {
            $photo->storeAs('public/info', $random_name_photo);

            $data['image'] = $random_name_photo;
        }
        $informations = Information::create($data);

        return redirect()->route('Information.index')->with('message', 'Data Information Berhasil Di tambah!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Information $informations)
    {
        return Inertia::render('Admin/Information/Show', [
            'informations' => $informations->with(['getinformations', 'keepinformations'])->find(Request::input('slug'))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $informations)
    {
        return Inertia::render('Admin/Information/Edit', [
            'informations' => $informations->with(['getinformations', 'keepinformations'])->find(Request::input('slug'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInformationRequest $request, Information $informations)
    {
        $informations = Information::find($request->slug);
        $informations->update($request->all());

        return redirect()->route('Information.index')->with('message', 'Data Information Berhasil Di Ubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $informations)
    {
        $informations = Information::find(Request::input('slug'));
        if (Storage::exists('public/info/'. $informations->image)){
            Storage::delete('public/info/'. $informations->image);
        }
        $informations->delete();

        return redirect()->route('Information.index')->with('message', 'Data Information Berhasil Di Hapus!!');
    }


    // Get Data api

    public function getAllData(){
        $informations = Information::all();

        return response()->json($informations);
    }
}
