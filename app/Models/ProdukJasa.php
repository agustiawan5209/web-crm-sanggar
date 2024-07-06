<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukJasa extends Model
{
    use HasFactory;

    protected $table = 'produk_jasas';

    protected $fillable = [
        'nama',
        'harga',
        'keterangan',
        'status'
    ];

    public function image(){
        return $this->hasMany(Gambar::class, 'jasa_id', 'id');
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
}
