<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sheep_id' => 'required|exists:sheep,id',
            'vaccine_name' => 'required|string',
            'dose' => 'nullable|string',
            'date_administered' => 'required|date',
            'next_due_date' => 'nullable|date|after:date_administered',
        ]);

        $validated['administered_by'] = auth()->id() ?? null;

        Vaccination::create($validated);

        return back()->with('success', 'Vaccination logged successfully.');
    }

    public function destroy($id)
    {
        $vaccination = Vaccination::findOrFail($id);
        $vaccination->delete();
        return back()->with('success', 'Vaccination record deleted.');
    }
}
