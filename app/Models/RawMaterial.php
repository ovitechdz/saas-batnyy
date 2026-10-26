<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'stock_quantity',
        'unit',
    ];

    //
}
