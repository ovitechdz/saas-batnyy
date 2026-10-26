<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedSupplier extends Model
{
    protected $fillable = [
        'farm_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
    ];

    //
}
