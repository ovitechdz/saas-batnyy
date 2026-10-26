<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaterSource;
use App\Models\WaterReading;
use App\Models\Farm;

class WaterManagementController extends Controller
{
    public function index(Request $request)
    {
        $farmId = auth()->user()->farm_id ?? Farm::first()->id;
        $sources = WaterSource::where('farm_id', $farmId)->get();
        
        // Mock aggregate data for dashboard
        $totalCapacity = $sources->sum('capacity');
        
        return view('water.index', compact('sources', 'totalCapacity'));
    }

    public function create()
    {
        $farmId = auth()->user()->farm_id ?? Farm::first()->id;
        $zones = \App\Models\FarmZone::where('farm_id', $farmId)->get();
        return view('water.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $farmId = auth()->user()->farm_id ?? Farm::first()->id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'zone_id' => 'nullable|exists:farm_zones,id',
            'flow_rate' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'coordinates' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
        ]);

        $validated['farm_id'] = $farmId;
        WaterSource::create($validated);

        return redirect()->route('water.index')->with('success', 'Water source added successfully.');
    }

    public function show($id)
    {
        $source = WaterSource::with('readings')->findOrFail($id);
        return view('water.show', compact('source'));
    }

    public function edit($id)
    {
        $source = WaterSource::findOrFail($id);
        $farmId = auth()->user()->farm_id ?? Farm::first()->id;
        $zones = \App\Models\FarmZone::where('farm_id', $farmId)->get();
        return view('water.edit', compact('source', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $source = WaterSource::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'zone_id' => 'nullable|exists:farm_zones,id',
            'flow_rate' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'coordinates' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
        ]);

        $source->update($validated);

        return redirect()->route('water.show', $id)->with('success', 'Water source updated successfully.');
    }

    public function destroy($id)
    {
        $source = WaterSource::findOrFail($id);
        $source->delete();

        return redirect()->route('water.index')->with('success', 'Water source removed.');
    }

    public function storeReading(Request $request, $sourceId)
    {
        $source = WaterSource::findOrFail($sourceId);
        
        $validated = $request->validate([
            'reading_date' => 'required|date',
            'consumption' => 'required|numeric|min:0',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'salinity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $source->readings()->create($validated);

        return redirect()->route('water.show', $sourceId)->with('success', 'Water reading logged successfully.');
    }
}
