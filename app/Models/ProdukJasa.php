<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukJasa extends Model
{
    use HasFactory;

    protected $table = 'produk_jasas';

    protected $fillable = [
        'nama',
        'harga',
        'biaya_transportasi',
        'keterangan',
        'status'
    ];

    public function image(){
        return $this->hasMany(Gambar::class, 'jasa_id', 'id');
    }

    protected $appends = [
        'rupiah',
        'harga_jasa',
        'harga_transportasi',

    ];

    public function rupiah(): Attribute
    {
        return new Attribute(
            get: fn()=> "Rp.".number_format($this->harga, 0,2),
        );
    }
    public function hargaJasa(): Attribute
    {
        return new Attribute(
            get: fn()=> "Rp.".number_format($this->harga, 0,2),
        );
    }
    public function hargaTransportasi(): Attribute
    {
        return new Attribute(
            get: fn()=> "Rp.".number_format($this->biaya_transportasi, 0,2),
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('harga', 'like', '%' . $search . '%');
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }

    public function review(){
        return $this->hasMany(Review::class,"produk_id","id");
    }
}
