<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaans';

    protected $fillable = [
        'customer_id',
        'customer_user',
        'jenis',
        'produk_id',
        'produk',
        'tgl_pengambilan',
        'tgl_pengembalian',
        'status',
        'tipe_bayar',
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function pembayaran(){
        return $this->hasOne(Pembayaran::class,'penyewaan_id','id');
    }
    public function review(){
        return $this->hasOne(Review::class,'penyewaan_id','id');
    }

    protected $casts = [
        'produk_id'=> 'json',
        'customer_user'=> 'json',
    ];

    protected $appends = [
        'kode_transaksi',
    ];

    public function kodeTransaksi(): Attribute
    {
        return new Attribute(
            get:fn()=> $this->pembayaran()->first()->kode_transaksi,
            set: null,
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('jenis', 'like', '%' . $search . '%')
                ->orWhere('produk', 'like', '%' . $search . '%')
                ->orWhereHas('customer', function($query, $search){
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
