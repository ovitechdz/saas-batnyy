<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedOrder extends Model
{
    protected $fillable = [
        'farm_id',
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'total_amount',
        'status',
    ];

    //
}
