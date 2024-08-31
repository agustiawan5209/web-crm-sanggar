<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('Bendahara')) {
            return redirect('/admin/dashboard');
        }

        if ($user->hasRole('customer')) {
            return redirect('/customer/dashboard');
        }
    }
}
