<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FeedingPlan extends Model
{
    use HasUuids;
    protected $fillable = [
        'farm_id',
        'name',
        'target_group',
        'feed_inventory_id',
        'daily_ration_kg',
        'schedule_time',
        'is_active',
    ];

    //
}
