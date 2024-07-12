<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskons';

    protected $fillable = [
        'nama',
        'keterangan',
        'jasa_id',
        'alat_id',
        'jenis',
        'jumlah',
        'kadaluarsa',
    ];

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('jenis', 'like', '%' . $search . '%');
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }

    public function getdiskon(): HasOne
    {
        return $this->hasOne(GetDiskon::class,'diskon_id','id');
    }
    public function keepdiskon(): HasOne
    {
        return $this->hasOne(KeepDiskon::class,'diskon_id','id');
    }
}
