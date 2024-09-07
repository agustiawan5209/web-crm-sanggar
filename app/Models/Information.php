<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
    ];

    protected $appends = [
        'gambar',
    ];

    public function gambar(): Attribute
    {
        return new Attribute(
            get: fn()=> asset('storage/info/'. $this->image),
        );
    }

    public function scopeFilter($query, $filter)
    {
        $query->when($filter['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
        })->when($filter['order'] ?? null, function ($query, $order) {
            $query->orderBy('id', $order);
        });
    }
}
