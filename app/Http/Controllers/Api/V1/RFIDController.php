<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SheepRepositoryInterface;
use Illuminate\Http\Request;

class RFIDController extends Controller
{
    protected SheepRepositoryInterface $sheepRepository;

    public function __construct(SheepRepositoryInterface $sheepRepository)
    {
        $this->sheepRepository = $sheepRepository;
    }

    /**
     * Scan RFID tag and return sheep details.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'rfid_tag' => 'required|string'
        ]);

        $sheep = $this->sheepRepository->findByRfid($request->rfid_tag);

        if (!$sheep) {
            return $this->errorResponse('No sheep found with this RFID tag.', 404);
        }

        // Load relations like health_records, vaccinations if needed
        $sheep->load(['healthRecords', 'vaccinations']);

        return $this->successResponse(
            new \App\Http\Resources\LivestockResource($sheep),
            'Sheep retrieved successfully'
        );
    }
}
