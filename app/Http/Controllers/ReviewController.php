<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'reviews'; // Ganti dengan nama tabel yang Anda inginkan
        // $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $columns[] = 'id';
        $columns[] = 'nama_customer';
        $columns[] = 'nama_produk';
        $columns[] = 'rating';
        $columns[] = 'comment';
        // $columns[] = 'status_pelanggan';

        return Inertia::render('Admin/Review/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id', 'deskripsi'])),
            'data' => Review::with(['user'])->filter(Request::only('search', 'order'))->paginate(10),
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        // $data['user'] = auth()->user();

        $review = Review::create($data);

        return redirect()->back()->with("message", "Review Berhasil Diberikan");
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $customer = Review::find(Request::input('slug'));
        $customer->delete();
        return redirect()->route('Review.index')->with('message', 'Data Review Berhasil Di Hapus!!');
    }
}
