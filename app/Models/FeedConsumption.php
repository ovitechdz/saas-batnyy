<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedConsumption extends Model
{
    protected $fillable = [
        'animal_id',
        'feed_type_id',
        'quantity_kg',
        'date',
    ];

    //
}
