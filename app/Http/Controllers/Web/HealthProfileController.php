<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Animal;

class HealthProfileController extends Controller
{
    public function show(string $id)
    {
        $animal = Animal::with([
            'medicalRecords.diagnoses.disease',
            'medicalRecords.treatments.medicineUsages.medicine',
            'medicalRecords.veterinaryVisit',
            'vaccinationRecords.vaccine',
            'quarantineRecords',
            'labResults',
            'heatCycles',
            'pregnancyRecords',
            'pedigree'
        ])->findOrFail($id);

        return view('health.profile', compact('animal'));
    }
}
