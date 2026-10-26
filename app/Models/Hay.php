<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hay extends Model
{
    protected $fillable = [
        'batch_number',
        'quantity_bales',
        'weight_per_bale_kg',
        'harvest_date',
    ];

    //
}
