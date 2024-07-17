<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'customers'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'nama_customer';
        $columns[] = 'phone';
        $columns[] = 'alamat';

        return Inertia::render('Admin/Customer/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Customer::with(['user'])->filter(Request::only('search', 'order'))->paginate(10),
            'can' => [
                'add' => false,
                'edit' => false,
                'show' => false,
                'delete' => false,
                'reset_password' => false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Customer/Form', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'phone'=> $request->no_telpon,
        ]);
        $role = Role::findByName('Customer');
        if ($role) {
            $user->assignRole($role); // Assign 'user' role to the user
            // $user->givePermissionTo([
            //     'add antrian',
            //     'edit antrian',
            //     'delete antrian',
            //     'show antrian',
            // ]);
        }

        $customer = new Customer([
            'user_id' => $user->id,
            'nama'=> $request->name,
            'alamat' => $request->alamat,
            'status'=> '0',
        ]);
        $customer->save();

        return redirect()->route('Customer.index')->with('message', 'Data Pasien Berhasil Di Simpan!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return Inertia::render('Admin/Customer/Show', [
            'customer' => Customer::with(['user'])->find(Request::input('slug'))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Admin/Customer/Edit', [
            'customer' => Customer::with(['user'])->find(Request::input('slug'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {

        $customer = Customer::find(Request::input('slug'));
        $user = User::find($customer->user_id)->update(['name'=> $request->name]);
        $customer->update([
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_telpon' => $request->no_telpon,
        ]);

        return redirect()->route('Customer.index')->with('message', 'Data Customer Berhasil Di Ubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer = Customer::find(Request::input('slug'));
        $customer->delete();
        return redirect()->route('Customer.index')->with('message', 'Data Customer Berhasil Di Hapus!!');
    }

    /**
     * Rest Password the specified resource.
     */
    public function resetpassword(Customer $customer)
    {

        return Inertia::render('Admin/Customer/UpdatePassword', [
            'user' => User::find(Request::input('slug')),
        ]);
    }
    public function resetpasswordUpdate(Customer $customer)
    {

        Request::validate([
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);

        $user = User::find(Request::input('slug'));
        $user->update([
            'remember_token' => Str::random(60),
            'password' => Hash::make(Request::input('password')),
        ]);
        return redirect()->route('Customer.index')->with('message', 'Password Customer berhasil Di Ubah!');

    }
}
