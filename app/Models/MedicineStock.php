<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStock extends Model
{
    protected $fillable = [
        'medicine_name',
        'quantity',
        'expiry_date',
    ];

    //
}
