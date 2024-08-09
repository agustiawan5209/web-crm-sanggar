<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            "alamat" => "required|string|max:200",
            "no_telpon" => "required|unique:users,phone",
            'username' => 'required|string|max:255|unique:users,username',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'phone' => $request->no_telpon,
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
            'nama' => $request->name,
            'alamat' => $request->alamat,
            'status' => '0',
        ]);
        $customer->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('Customer.dashboard');
    }
}
