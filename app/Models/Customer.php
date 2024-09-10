<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'status'
    ];
    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $appends = [
        'nama_customer',
        'no_telpon',
        'status_pelanggan'
    ];

    public function namaCustomer(): Attribute
    {
        return new Attribute(
            get: fn() => $this->user()->first()->name,
        );
    }
    public function noTelpon(): Attribute
    {
        return new Attribute(
            get: fn() => $this->user()->first()->phone,
        );
    }
    public function statusPelanggan(): Attribute
    {
        $s = "0 = Pelanggan Baru, 1=Pelanggan Aktif, 2 = Pelanggan Lama";

        $status = $this->status;
        switch ($status) {
            case '0' || 0:
                $value = "Pelanggan Baru";
                break;
            case '1' || 1:
                $value = "Pelanggan Aktif";
                break;
            case '2' || 2:
                $value = "Pelanggan Lama";
                break;

            default:
                $value = "Pelanggan Baru";
                break;
        }
        return new Attribute(
            get: fn() => $value,
        );
    }



    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('no_telpon', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
