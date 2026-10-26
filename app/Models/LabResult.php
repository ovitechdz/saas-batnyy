<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'medical_record_id',
        'test_type',
        'test_date',
        'result_status',
        'notes',
        'attachment_path',
        'recorded_by',
    ];

    //
}
