<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalPurchase extends Model
{
    protected $fillable = [
        'animal_id',
        'farm_id',
        'purchase_price',
        'supplier_id',
        'purchase_date',
    ];

    //
}
