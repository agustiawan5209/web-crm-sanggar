<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    use HasFactory;

    protected $table = 'gambars';

    protected $fillable = [
        'jasa_id',
        'alat_id',
        'image',
        'status'
    ];

    public function jasa(){
        return $this->hasOne(ProdukJasa::class, 'id','jasa_id');
    }
    public function alat(){
        return $this->hasOne(ProdukAlat::class, 'id','alat_id');
    }
    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('no_telpon', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($query, $search){
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }

    public function scopeDefault($query){
        $query->where('status', '1');
    }

    public function getPhotoAttribute($value)
    {
        return url('storage/produk/' . $value);
    }
}
