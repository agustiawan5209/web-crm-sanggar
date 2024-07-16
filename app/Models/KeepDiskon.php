<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeepDiskon extends Model
{
    use HasFactory;

    protected $table = 'keep_diskons';
    protected $fillable = ['diskon_id','min_frequency'];
    public function diskon(){
        return $this->hasOne(Diskon::class, 'id', 'diskon_id');
    }
}
