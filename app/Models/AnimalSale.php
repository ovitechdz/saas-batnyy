<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalSale extends Model
{
    protected $fillable = [
        'animal_id',
        'farm_id',
        'sale_price',
        'buyer_id',
        'sale_date',
    ];

    //
}
