<?php

namespace App\Models;

use Carbon\Carbon;
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
        'sub_total',
        'total',
        'jenis_bayar',
        'tgl',
        'status',
        'keterangan',
    ];

    protected $appends = [
        'bukti_url',
        'total_transaksi',
        'sub_total_transaksi',
        'produk',
        'human_format',
    ];

    // Accessor untuk formatted created_at
    public function humanFormat(): Attribute
    {
        return new Attribute(
            get: function () {
                // Set locale ke bahasa Indonesia
                Carbon::setLocale('id');
                $createdAt = Carbon::parse($this->created_at);
                $expirationTime = $createdAt->addHour();
                $now = Carbon::now();

                if ($this->jenis_bayar == 'Bayar Nanti') {
                    if ($now->greaterThan($expirationTime)) {
                        // Hapus data jika waktu pembayaran sudah berakhir
                        Penyewaan::find($this->penyewaan_id)->delete();
                        return "Waktu pembayaran sudah berakhir dan data telah dihapus.";
                    }
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

    public function totalTransaksi(): Attribute
    {
        return new Attribute(
            get: fn() => "Rp." . number_format($this->total, 0, 2),
        );
    }
    public function subTotalTransaksi(): Attribute
    {
        return new Attribute(
            get: fn() => "Rp." . number_format($this->sub_total, 0, 2),
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
        if ($order == null) {
            $query->orderBy('id', 'desc');
        } else {
            $query->when($order ?? null, function ($query) use ($order) {
                $query->orderBy('id', $order);
            });
        }
    }
}
