<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukAlat extends Model
{
    use HasFactory;

    protected $table = 'produk_alats';

    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'keterangan',
        'status'
    ];

    protected $appends = [
        'rupiah',
    ];

    public function rupiah(): Attribute
    {
        return new Attribute(
            get: fn()=> "Rp.".number_format($this->harga, 0,2),
        );
    }


    public function image(){
        return $this->hasMany(Gambar::class, 'alat_id', 'id');
    }
    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('harga', 'like', '%' . $search . '%')
                ->orWhere('stok', 'like', '%' . $search . '%');
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
