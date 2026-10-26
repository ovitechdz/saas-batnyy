<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sheep_id' => 'required|exists:sheep,id',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'treatment_cost' => 'nullable|numeric|min:0',
            'next_checkup_date' => 'nullable|date',
            'medications' => 'nullable|string',
            'date_recorded' => 'required|date',
        ]);

        $validated['vet_id'] = auth()->id() ?? null;

        HealthRecord::create($validated);

        return back()->with('success', 'Health record added successfully.');
    }

    public function destroy($id)
    {
        $record = HealthRecord::findOrFail($id);
        $record->delete();
        return back()->with('success', 'Health record deleted.');
    }
}
