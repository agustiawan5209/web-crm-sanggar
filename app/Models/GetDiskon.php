<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetDiskon extends Model
{
    use HasFactory;

    protected $fillable = ['diskon_id','min_quantity'];

}
