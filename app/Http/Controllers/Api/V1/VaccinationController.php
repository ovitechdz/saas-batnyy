<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use App\Http\Resources\VaccinationResource;
use App\Http\Requests\StoreVaccinationRequest;

class VaccinationController extends Controller
{
    public function index(Request $request)
    {
        $vaccinations = Vaccination::query();
        if ($request->has('sheep_id')) {
            $vaccinations->where('sheep_id', $request->sheep_id);
        }
        return $this->successResponse(
            VaccinationResource::collection($vaccinations->get()),
            'Vaccinations retrieved successfully'
        );
    }

    public function store(StoreVaccinationRequest $request)
    {
        $vaccination = Vaccination::create($request->validated());
        return $this->successResponse(
            new VaccinationResource($vaccination),
            'Vaccination recorded successfully',
            201
        );
    }

    public function destroy($id)
    {
        $vaccination = Vaccination::find($id);
        if (!$vaccination) {
            return $this->errorResponse('Vaccination not found', 404);
        }
        $vaccination->delete();
        return $this->successResponse(null, 'Vaccination deleted successfully');
    }
}
