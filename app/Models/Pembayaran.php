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
            get: fn()=> "Rp.".number_format($this->total, 0,2),
        );
    }

    public function buktiUrl(): Attribute
    {
        return new Attribute(
            get: fn()=> asset('storage/bukti_bayar/'. $this->bukti),
        );
    }

    public function penyewaan()
    {
        return $this->hasOne(Penyewaan::class,'id','penyewaan_id');
    }

    public function produk():Attribute
    {
        return new Attribute(
            get: fn()=> $this->penyewaan()->first()->produk,
        );
    }
}
