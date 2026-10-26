<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FeedInventory;
use App\Http\Requests\StoreFeedInventoryRequest;
use App\Http\Resources\FeedInventoryResource;
use Illuminate\Http\Request;

class FeedInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = FeedInventory::query();
        if ($request->has('farm_id')) {
            $query->where('farm_id', $request->farm_id);
        }

        return $this->successResponse(
            FeedInventoryResource::collection($query->get()),
            'Feed inventory retrieved successfully'
        );
    }

    public function store(StoreFeedInventoryRequest $request)
    {
        $inventory = FeedInventory::create($request->validated());

        return $this->successResponse(
            new FeedInventoryResource($inventory),
            'Feed inventory created successfully',
            201
        );
    }

    public function show(string $id)
    {
        $inventory = FeedInventory::findOrFail($id);

        return $this->successResponse(
            new FeedInventoryResource($inventory),
            'Feed inventory retrieved successfully'
        );
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity_kg' => 'numeric|min:0',
            'reorder_level_kg' => 'numeric|min:0'
        ]);

        $inventory = FeedInventory::findOrFail($id);
        $inventory->update($request->only(['quantity_kg', 'reorder_level_kg']));

        return $this->successResponse(
            new FeedInventoryResource($inventory),
            'Feed inventory updated successfully'
        );
    }

    public function destroy(string $id)
    {
        FeedInventory::destroy($id);

        return $this->successResponse(
            null,
            'Feed inventory deleted successfully'
        );
    }
}
