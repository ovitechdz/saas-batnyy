<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrediction extends Model
{
    protected $fillable = [
        'farm_id',
        'model_id',
        'target_id',
        'target_type',
        'prediction_type',
        'confidence_score',
        'prediction_data',
        'is_acknowledged',
    ];

    //
}
