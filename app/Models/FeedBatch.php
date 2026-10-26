<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedBatch extends Model
{
    protected $fillable = [
        'feed_inventory_id',
        'batch_number',
        'supplier_id',
        'quantity_received',
        'quantity_remaining',
        'delivery_date',
        'expiration_date',
        'unit_cost',
    ];

    //
}
