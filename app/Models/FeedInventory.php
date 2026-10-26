<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedInventory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'feed_inventory';

    protected $fillable = [
        'farm_id',
        'category_id',
        'name',
        'total_quantity',
        'unit',
        'minimum_threshold',
        'warehouse_id',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'unit_price' => 'decimal:2',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function nutritionProfile()
    {
        return $this->hasOne(NutritionProfile::class, 'feed_inventory_id');
    }
}
