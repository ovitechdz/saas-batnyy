<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FeedCategory extends Model
{
    use HasUuids;
    protected $fillable = [
        'farm_id',
        'name',
        'description',
    ];

    //
}
