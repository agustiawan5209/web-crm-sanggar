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
        'rating'=> 'integer'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function produk()
    {
        if($this->jenis == 'alat'){
            return $this->belongsTo(ProdukAlat::class, 'produk_id', 'id');
        }
        if($this->jenis == 'jasa'){
            return $this->belongsTo(ProdukJasa::class, 'produk_id', 'id');
        }
        return null;
    }

    protected $appends = [
        'nama_customer',
        'nama_produk',
    ];

    public function namaCustomer(): Attribute
    {
        return new Attribute(
            get: fn () => $this->user()->first()?->name ?? null,
        );
    }
    public function namaProduk(): Attribute
    {
        // dd($this->produk());
        return new Attribute(
            get: fn () => $this->produk()?->first()?->nama ?? "Produk Hilang",
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('jenis', 'like', '%' . $search . '%')
                ->orWhere('rating', 'like', '%' . $search . '%')
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
