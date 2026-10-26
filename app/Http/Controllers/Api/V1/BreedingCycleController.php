<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BreedingCycle;
use Illuminate\Http\Request;
use App\Http\Resources\BreedingCycleResource;
use App\Http\Requests\StoreBreedingCycleRequest;

class BreedingCycleController extends Controller
{
    public function index(Request $request)
    {
        $cycles = BreedingCycle::query();
        if ($request->has('sheep_id')) {
            $cycles->where('sheep_id', $request->sheep_id);
        }
        return $this->successResponse(
            BreedingCycleResource::collection($cycles->get()),
            'Breeding cycles retrieved successfully'
        );
    }

    public function store(StoreBreedingCycleRequest $request)
    {
        $cycle = BreedingCycle::create($request->validated());
        return $this->successResponse(
            new BreedingCycleResource($cycle),
            'Breeding cycle recorded successfully',
            201
        );
    }

    public function destroy($id)
    {
        $cycle = BreedingCycle::find($id);
        if (!$cycle) {
            return $this->errorResponse('Breeding cycle not found', 404);
        }
        $cycle->delete();
        return $this->successResponse(null, 'Breeding cycle deleted successfully');
    }
}
