<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Disease;
use App\Models\Treatment;
use App\Models\VaccinationRecord;
use App\Models\Medicine;
use App\Models\VeterinaryVisit;

class HealthApiController extends Controller
{
    public function getHealthRecords()
    {
        return response()->json(MedicalRecord::with(['diagnoses.disease', 'treatments'])->paginate(20));
    }

    public function getDiseases()
    {
        return response()->json(Disease::all());
    }

    public function getTreatments()
    {
        return response()->json(Treatment::with('medicineUsages.medicine')->paginate(20));
    }

    public function getVaccinations()
    {
        return response()->json(VaccinationRecord::with('vaccine')->paginate(20));
    }

    public function getMedicines()
    {
        return response()->json(Medicine::with('stock')->paginate(20));
    }

    public function getVeterinaryVisits()
    {
        return response()->json(VeterinaryVisit::with('veterinarian')->paginate(20));
    }
}
