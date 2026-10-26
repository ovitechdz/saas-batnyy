<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineUsage extends Model
{
    protected $fillable = [
        'animal_id',
        'medicine_id',
        'dose_ml',
        'usage_date',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
