<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmBarn;
use App\Models\FarmZone;
use App\Models\Farm;
use Illuminate\Support\Str;

class BarnController extends Controller
{
    public function index()
    {
        $farm = Farm::first();
        $barns = FarmBarn::with('zone')->get();
        $zones = FarmZone::all();
        
        return view('farm.barns.index', compact('barns', 'zones', 'farm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'zone_id' => 'required|exists:farm_zones,id',
            'type' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        $farm = Farm::first();

        FarmBarn::create([
            'id' => Str::uuid(),
            'farm_id' => $farm->id,
            'zone_id' => $request->zone_id,
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'current_occupancy' => 0,
            'temperature' => rand(20, 25), // Mock data
            'humidity' => rand(40, 60), // Mock data
            'status' => 'active',
        ]);

        return redirect()->route('farm.barns.index')->with('success', 'Barn created successfully.');
    }
}
