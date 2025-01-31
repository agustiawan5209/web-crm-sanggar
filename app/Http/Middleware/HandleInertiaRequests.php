<?php

namespace App\Http\Middleware;

use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $orders = [];
        if(Auth::check()){
            $user = User::with(['customer'])->find(Auth::user()->id);
            $orders = Penyewaan::where('customer_id', $user->customer->id)->get();
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'role' => auth()->hasUser() ? auth()->user()->getRoleNames()->toArray() : [],
                'order'=> $orders,
            ],
            'message'=> fn () => $request->session()->get('message'),
        ];
    }
}
