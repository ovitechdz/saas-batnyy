<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedCost extends Model
{
    protected $fillable = [
        'feed_batch_id',
        'total_cost',
        'currency',
    ];

    //
}
