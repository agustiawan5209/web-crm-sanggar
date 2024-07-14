<?php

namespace App\Http\Controllers\Customer;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\MustVerifyEmail;
use Inertia\Response;


class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Dashboard');
    }
    public function profile(Request $request): Response
    {
        return Inertia::render('User/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
}
