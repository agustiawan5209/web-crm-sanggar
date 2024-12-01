<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
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
            case '0':
                $value = "Pelanggan Baru";
                break;
            case '1':
                $value = "Pelanggan Aktif";
                break;
            case '2':
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
            $query->where('alamat', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                    $query->orWhere('phone', 'like', '%' . $search . '%');
                });
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }

    public function updateStatus()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $sewaCount = $this->penyewaan()
                            ->where('created_at', '>=', $sixMonthsAgo)
                            ->count();

        if ($sewaCount > 3) {
            $this->status = '1';
        } elseif ($this->penyewaan()->where('created_at', '<', $sixMonthsAgo)->exists()) {
            $this->status = '2';
        } else {
            $this->status = '0';
        }

        $this->save();
    }
}
