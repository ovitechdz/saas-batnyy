<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Http\Requests\StoreHealthRecordRequest;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthRecord::query();

        if ($request->has('sheep_id')) {
            $query->where('sheep_id', $request->sheep_id);
        }

        $records = $query->with('sheep')->orderBy('record_date', 'desc')->get();

        return $this->successResponse(
            \App\Http\Resources\HealthRecordResource::collection($records),
            'Health records retrieved successfully'
        );
    }

    public function store(StoreHealthRecordRequest $request)
    {
        $record = HealthRecord::create($request->validated());

        return $this->successResponse(
            new \App\Http\Resources\HealthRecordResource($record),
            'Health record created successfully',
            201
        );
    }

    public function show(string $id)
    {
        $record = HealthRecord::findOrFail($id);
        return $this->successResponse(
            new \App\Http\Resources\HealthRecordResource($record),
            'Health record retrieved successfully'
        );
    }

    public function update(Request $request, string $id)
    {
        $record = HealthRecord::findOrFail($id);
        $record->update($request->all());

        return $this->successResponse(
            new \App\Http\Resources\HealthRecordResource($record),
            'Health record updated successfully'
        );
    }

    public function destroy(string $id)
    {
        HealthRecord::destroy($id);
        return $this->successResponse(
            null,
            'Health record deleted successfully'
        );
    }
}
