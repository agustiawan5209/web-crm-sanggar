<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetDiskon extends Model
{
    use HasFactory;

    protected $table = 'get_diskons';
    protected $fillable = ['diskon_id','min_quantity'];

    protected $casts = [
        'min_quantity'=> 'integer',
    ];
    public function diskon(){
        return $this->hasOne(Diskon::class, 'id', 'diskon_id');
    }

}
