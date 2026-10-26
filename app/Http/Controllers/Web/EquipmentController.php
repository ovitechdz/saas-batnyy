<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmEquipment;
use App\Models\Farm;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    public function index()
    {
        $farm = Farm::first();
        $equipmentList = FarmEquipment::all();
        $zones = \App\Models\FarmZone::all();
        
        return view('farm.equipment.index', compact('equipmentList', 'farm', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'power_type' => 'required|string|max:50',
            'zone_id' => 'nullable|exists:farm_zones,id',
            'status' => 'required|string|in:Active,Maintenance,Idle',
        ]);

        $farm = Farm::first();

        FarmEquipment::create([
            'id' => Str::uuid(),
            'farm_id' => $farm->id,
            'name' => $request->name,
            'serial_number' => $request->serial_number,
            'type' => $request->type,
            'power_type' => $request->power_type,
            'zone_id' => $request->zone_id,
            'status' => $request->status,
            'fuel_level' => 100, // Default full battery/fuel
            'next_maintenance_date' => now()->addMonths(3), // Default next maintenance
        ]);

        return redirect()->route('farm.equipment.index')->with('success', 'Equipment added successfully.');
    }
}
