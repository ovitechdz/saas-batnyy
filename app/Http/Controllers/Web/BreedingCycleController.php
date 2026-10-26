<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BreedingCycle;
use App\Models\Animal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BreedingCycleController extends Controller
{
    public function index()
    {
        $farmId = auth()->user()->farm_id ?? \App\Models\Farm::first()->id ?? null;
        if (!$farmId) {
            return redirect()->route('dashboard')->with('error', 'No farm associated with your account.');
        }

        // Fetch using the new BreedingCycle model
        $cycles = BreedingCycle::with(['ewe', 'ram'])
            ->whereHas('ewe', fn($q) => $q->where('farm_id', $farmId))
            ->orderBy('start_date', 'desc')
            ->paginate(15);
        
        $stats = [
            'pregnant' => BreedingCycle::whereHas('ewe', fn($q) => $q->where('farm_id', $farmId))->where('pregnancy_status', 'pregnant')->count(),
            'completed' => BreedingCycle::whereHas('ewe', fn($q) => $q->where('farm_id', $farmId))->where('status', 'completed')->count(),
            'failed' => BreedingCycle::whereHas('ewe', fn($q) => $q->where('farm_id', $farmId))->whereIn('pregnancy_status', ['aborted', 'open'])->count(),
        ];

        $ewes = Animal::where('farm_id', $farmId)->where('gender', 'F')->get();
        $rams = Animal::where('farm_id', $farmId)->where('gender', 'M')->get();

        return view('breeding.index', compact('cycles', 'stats', 'ewes', 'rams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'ram_id' => 'nullable|exists:animals,id',
            'breeding_method' => 'required|string',
            'mating_type' => 'nullable|string',
            'bcs_at_mating' => 'nullable|numeric|min:1|max:5',
            'start_date' => 'required|date',
            'scan_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $expectedBirth = $startDate->copy()->addDays(150);
        
        BreedingCycle::create([
            'animal_id' => $request->animal_id,
            'ram_id' => $request->ram_id,
            'breeding_method' => $request->breeding_method,
            'mating_type' => $request->mating_type,
            'bcs_at_mating' => $request->bcs_at_mating,
            'start_date' => $startDate->format('Y-m-d'),
            'expected_birth_date' => $expectedBirth->format('Y-m-d'),
            'scan_date' => $request->scan_date,
            'pregnancy_status' => 'unknown',
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Breeding cycle logged successfully.');
    }

    public function update(Request $request, $id)
    {
        $cycle = BreedingCycle::findOrFail($id);

        $request->validate([
            'pregnancy_status' => 'nullable|string|in:unknown,pregnant,open,aborted',
            'fetus_count' => 'nullable|integer',
            'status' => 'nullable|string|in:pending,completed,failed',
        ]);

        $cycle->update($request->only(['pregnancy_status', 'fetus_count', 'status']));

        return back()->with('success', 'Breeding cycle updated.');
    }
}
