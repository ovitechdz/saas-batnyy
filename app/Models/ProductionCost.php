<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCost extends Model
{
    protected $fillable = [
        'production_batch_id',
        'cost_type',
        'amount',
    ];

    //
}
