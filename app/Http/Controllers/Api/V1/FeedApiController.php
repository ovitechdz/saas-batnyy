<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FeedInventory;
use App\Models\Warehouse;
use App\Models\FeedSupplier;
use App\Models\ProductionBatch;

class FeedApiController extends Controller
{
    public function getInventory()
    {
        return response()->json(FeedInventory::with('nutritionProfile')->paginate(20));
    }

    public function getWarehouses()
    {
        return response()->json(Warehouse::all());
    }

    public function getSuppliers()
    {
        return response()->json(FeedSupplier::all());
    }

    public function getProductionBatches()
    {
        return response()->json(ProductionBatch::with('concentrate')->paginate(20));
    }
}
