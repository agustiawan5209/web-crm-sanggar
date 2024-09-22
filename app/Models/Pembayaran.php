<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'kode_transaksi',
        'bukti',
        'penyewaan_id',
        'total',
        'jenis_bayar',
        'tgl',
        'status',
        'keterangan',
    ];

    protected $appends = [
        'bukti_url',
        'total_transaksi',
        'produk'
    ];

    public function totalTransaksi(): Attribute
    {
        return new Attribute(
            get: fn() => "Rp." . number_format($this->total, 0, 2),
        );
    }

    public function buktiUrl(): Attribute
    {
        return new Attribute(
            get: fn() => asset('storage/bukti_bayar/' . $this->bukti),
        );
    }

    public function penyewaan()
    {
        return $this->hasOne(Penyewaan::class, 'id', 'penyewaan_id');
    }

    public function produk(): Attribute
    {
        return new Attribute(
            get: fn() => $this->penyewaan()->first()->produk,
        );
    }

    public function scopeFilterBySearch($query, $search)
    {
        $query->where('kode_transaksi', 'like', '%' . $search . '%')
            ->orWhere('jenis_bayar', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orWhereHas('penyewaan', function ($query) use ($search) {
                $query->whereHas('customer', function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%');
                });
            });
    }

    public function scopeFilterOrderBy($query, $order)
    {
        if($order == null){
            $query->orderBy('id','desc');
        }else{
            $query->when($order ?? null, function ($query) use ($order) {
                $query->orderBy('id', $order);
            });
        }
    }
}
