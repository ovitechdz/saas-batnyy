<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmZone;
use App\Models\Farm;
use Illuminate\Support\Str;

class ZoneController extends Controller
{
    public function index()
    {
        // For multi-tenant, we should get the current farm. For now, get the first one.
        $farm = Farm::first(); 
        
        $zones = FarmZone::withCount('barns')->get();
        return view('farm.zones.index', compact('zones', 'farm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'area_size' => 'nullable|numeric',
            'coordinates' => 'nullable|string|max:255',
            'soil_type' => 'nullable|string|max:100',
            'status' => 'required|string|in:active,resting,maintenance',
        ]);

        $farm = Farm::first(); // Replace with Auth()->user()->farm_id logic

        FarmZone::create([
            'id' => Str::uuid(),
            'farm_id' => $farm->id ?? (Farm::create(['id' => Str::uuid(), 'name' => 'Default Farm'])->id),
            'name' => $request->name,
            'type' => $request->type,
            'area_size' => $request->area_size,
            'coordinates' => $request->coordinates ? json_encode(['raw' => $request->coordinates]) : null,
            'soil_type' => $request->soil_type,
            'status' => $request->status,
            'manager_id' => null, // Placeholder for future manager assignment
        ]);

        return redirect()->route('farm.zones.index')->with('success', 'Zone created successfully.');
    }
}
