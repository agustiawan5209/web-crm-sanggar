<?php

namespace App\Http\Controllers\Customer;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Mail\EmailNotification;
use App\Http\Controllers\Controller;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class CustomerController extends Controller
{
    public function index()
    {
        $penyewaan = Penyewaan::where('customer_id', Auth::user()->customer->id);
        $pembayaran = Pembayaran::whereHas('penyewaan', function ($query)  {
            $query->where('customer_id', Auth::user()->customer->id);
        });

        $subject = "Pemberitahuan Pendaftaran";
        $messageContent = "Selamat Datang Di Sistem Informasi Sanggar Seni Kawali.";

        // Mail::to('wawan@citratekno.com')->send(new EmailNotification( Auth::user(), 'sanggar-kawali.citratekno.com',$subject, $messageContent));
        return Inertia::render('User/Dashboard', [
            'penyewaan'=>  [
                'count'=> $penyewaan->count(),
                'sewa'=> $penyewaan->where('status', 'Dalam Penyewaan')->count(),
                'selesai'=> $penyewaan->where('status', 'SELESAI')->count(),
            ],
            'transaksi'=> [
                'PENDING'=> $pembayaran->where('status', 'PENDING')->count(),
                'SELESAI'=> $pembayaran->where('status', 'SELESAI')->count(),
                'DITERIMA'=> $pembayaran->where('status', 'DITERIMA')->count(),
            ],
        ]);
    }
    public function profile(Request $request): Response
    {
        return Inertia::render('User/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
}
