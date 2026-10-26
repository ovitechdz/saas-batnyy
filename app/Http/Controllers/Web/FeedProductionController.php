<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductionBatch;
use App\Models\RawMaterial;
use App\Models\Silage;

class FeedProductionController extends Controller
{
    public function index()
    {
        $silagePits = Silage::orderBy('harvest_date', 'desc')->get();
        $rawMaterials = RawMaterial::all();
        $recentBatches = ProductionBatch::with('concentrate')->orderBy('production_date', 'desc')->limit(10)->get();

        return view('feed.production', compact('silagePits', 'rawMaterials', 'recentBatches'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:255',
            'yield_quantity' => 'required|numeric|min:0',
            'production_date' => 'required|date',
        ]);

        $farm = \App\Models\Farm::first();
        if (!$farm) {
            return back()->with('error', 'No farm configured.');
        }

        $validated['farm_id'] = $farm->id;
        $validated['unit'] = 'kg'; // default unit
        // concentrate_id can be left null for custom mix for now

        ProductionBatch::create($validated);

        return back()->with('success', 'Production batch logged successfully.');
    }
    public function storeSilage(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'pit_name' => 'required|string|max:255',
            'harvest_date' => 'required|date',
            'estimated_tonnage' => 'required|numeric|min:0',
            'status' => 'required|string|in:Fermenting,Open,Empty',
        ]);

        \App\Models\Silage::create($validated);

        return back()->with('success', 'Silage pit recorded successfully.');
    }
}
