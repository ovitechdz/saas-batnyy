<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmZone;
use App\Models\FarmBarn;
use App\Models\FarmEquipment;

class FarmOperationsController extends Controller
{
    public function index()
    {
        $farmsCount = Farm::count();
        $zonesCount = FarmZone::count();
        $barnsCount = FarmBarn::count();
        $activeEquipment = FarmEquipment::where('status', 'active')->count();

        // Sample data for the 3D map rendering (if no DB data exists, we'll use placeholder in UI)
        $zones = FarmZone::with('farm')->get();
        $equipmentList = FarmEquipment::limit(5)->get();

        return view('farm.dashboard', compact('farmsCount', 'zonesCount', 'barnsCount', 'activeEquipment', 'zones', 'equipmentList'));
    }
}
