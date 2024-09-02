<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'produk_id',
        'jenis',
        'user_id',
        'user',
        'penyewaan_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'user' => "json",
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $appends = [
        'nama_customer',
    ];

    public function namaCustomer(): Attribute
    {
        return new Attribute(
            get: fn () => $this->user()->first()->name,
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('jenis', 'like', '%' . $search . '%')
                ->orWhere('rating', 'like', '%' . $search . '%');
                // ->orWhereHas('customer', function($query, $search){
                //     $query->where('name', 'like', '%' . $search . '%');
                // });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
