<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'bukti',
        'penyewaan_id',
        'total',
        'jenis_bayar',
        'tgl',
        'status',
    ];
}