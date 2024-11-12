<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaans';

    protected $fillable = [
        'struk',
        'customer_id',
        'customer_user',
        'jenis',
        'produk_id',
        'produk',
        'tgl_pengambilan',
        'tgl_pengembalian',
        'status',
        'jumlah',
        'tipe_bayar',
        'tgl_penyewaan',
        'lokasi',
        'ongkir',
        'biaya_ongkir',

    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'penyewaan_id', 'id');
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'penyewaan_id', 'id');
    }


    protected $casts = [
        'produk_id' => 'json',
        'customer_user' => 'json',
        'jumlah'=> 'integer',
        'biaya_ongkir'=> 'integer',
    ];

    protected $appends = [
        'kode_transaksi',
        'total_bayar',
        'human_format',
        'file_url',
        'pengiriman'
    ];

    public function pengiriman() : Attribute
    {
        return new Attribute(
            get: fn()=> $this->ongkir,
        );
    }
    public function fileUrl() : Attribute
    {
        return new Attribute(
            get: fn()=> asset('storage/'. $this->struk),
        );
    }
    // Accessor untuk formatted created_at
    public function humanFormat() : Attribute
    {
        return new Attribute(
            get: function() {
                $createdAt = Carbon::parse($this->created_at);
                $expirationTime = $createdAt->addHour();
                $now = Carbon::now();

                if ($now->greaterThan($expirationTime)) {
                    return "Waktu pembayaran sudah berakhir.";
                }

                $remainingTime = $now->diffForHumans($expirationTime, [
                    'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                    'parts' => 2,
                    'short' => true,
                ]);

                return "Sisa waktu pembayaran: $remainingTime";
            }
        );
    }

    public function kodeTransaksi(): Attribute
    {
        return new Attribute(
            get: fn() => $this->pembayaran()->first()->kode_transaksi,
            set: null,
        );
    }
    public function totalBayar(): Attribute
    {
        return new Attribute(
            get: fn() => "Rp. ". number_format($this->pembayaran()->first()->total,0,2),
            set: null,
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('jenis', 'like', '%' . $search . '%')
                ->orWhere('produk', 'like', '%' . $search . '%')
                ->orWhereDate('created_at', $search)
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            // dd($order);
            $query->orderBy('id', $order);
        });
    }
}
