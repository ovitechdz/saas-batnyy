<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLivestockRequest;
use App\Services\LivestockService;
use Illuminate\Http\Request;

class LivestockController extends Controller
{
    protected LivestockService $livestockService;

    public function __construct(LivestockService $livestockService)
    {
        $this->livestockService = $livestockService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Example: Get farm_id from authenticated user or query param
        $farmId = $request->query('farm_id');
        
        // For testing/MVP purposes: if no farm_id is provided, get the first farm
        if (!$farmId) {
            $farm = \App\Models\Farm::first();
            if ($farm) {
                $farmId = $farm->id;
            } else {
                return $this->errorResponse('Farm ID is required, and no default farm exists.', 400);
            }
        }

        $filters = $request->except(['farm_id', 'page']);
        $sheep = $this->livestockService->getFarmLivestock($farmId, $filters);

        return $this->successResponse(
            \App\Http\Resources\LivestockResource::collection($sheep),
            'Livestock retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivestockRequest $request)
    {
        try {
            $animalData = \App\DTO\AnimalData::fromRequest($request);
            $userId = $request->user()?->id ?? (string) (\App\Models\User::first()->id ?? 1);
            $sheep = $this->livestockService->registerAnimal($animalData, $userId);

            return $this->successResponse(
                \App\Http\Resources\LivestockResource::make($sheep),
                'Sheep registered successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logic to show specific sheep using Repository or Service
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic to update specific sheep
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logic to delete specific sheep
    }
}
