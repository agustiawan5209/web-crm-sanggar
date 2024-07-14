<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaans';

    protected $fillable = [
        'customer_id',
        'jenis',
        'produk',
        'tgl_pengambilan',
        'tgl_pengembalian',
        'status',
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function pembayaran(){
        return $this->belongsTo(Pembayaran::class,'customer_id','id');
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
