<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'medical_record_id',
        'treatment_type',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function medicineUsages()
    {
        return $this->hasMany(MedicineUsage::class);
    }
}
