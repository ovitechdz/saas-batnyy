<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use App\Models\EnergyLog;
use App\Models\PowerConsumption;
use App\Models\SolarPanel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EnergyManagementController extends Controller
{
    public function index(Request $request)
    {
        $farmId = session('active_farm_id');

        if (!$farmId) {
            // Fallback for development if no farm is in session
            $farmId = \App\Models\Farm::first()->id ?? null;
        }

        // Today's metrics
        $today = Carbon::today();
        
        $todayProduction = EnergyLog::where('farm_id', $farmId)
            ->whereDate('recorded_at', $today)
            ->sum('energy_produced_kwh');

        $todayConsumption = PowerConsumption::where('farm_id', $farmId)
            ->whereDate('recorded_at', $today)
            ->sum('energy_consumed_kwh');

        $netEnergy = $todayProduction - $todayConsumption;

        // Assets
        $panels = SolarPanel::where('farm_id', $farmId)->get();
        $batteries = Battery::where('farm_id', $farmId)->get();

        // Recent Logs (for charts or tables)
        $recentLogs = EnergyLog::where('farm_id', $farmId)
            ->with('solarPanel')
            ->orderBy('recorded_at', 'desc')
            ->limit(10)
            ->get();

        return view('energy.index', compact(
            'todayProduction',
            'todayConsumption',
            'netEnergy',
            'panels',
            'batteries',
            'recentLogs'
        ));
    }

    public function storeConsumption(Request $request)
    {
        $request->validate([
            'consumer_type' => 'required|string',
            'consumer_id' => 'nullable|string',
            'energy_consumed_kwh' => 'required|numeric|min:0.01',
            'recorded_at' => 'required|date',
        ]);

        $farmId = session('active_farm_id') ?? \App\Models\Farm::first()->id;

        PowerConsumption::create([
            'farm_id' => $farmId,
            'consumer_type' => $request->consumer_type,
            'consumer_id' => $request->consumer_id,
            'energy_consumed_kwh' => $request->energy_consumed_kwh,
            'recorded_at' => $request->recorded_at,
        ]);

        return redirect()->back()->with('success', 'Consumption logged successfully!');
    }
}
