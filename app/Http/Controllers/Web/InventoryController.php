<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedInventory;
use App\Models\Farm;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = FeedInventory::orderBy('name')->get();
        return view('inventory.index', compact('inventory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_quantity' => 'required|numeric|min:0',
            'minimum_threshold' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'unit_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $farm = Farm::first();
        if (!$farm) {
            return back()->with('error', 'No farm exists to associate this inventory with.');
        }
        $validated['farm_id'] = $farm->id;

        // Check if exists
        $existing = FeedInventory::where('farm_id', $farm->id)
            ->where('name', $validated['name'])
            ->first();

        if ($existing) {
            $existing->total_quantity += $validated['total_quantity'];
            $existing->minimum_threshold = $validated['minimum_threshold'];
            // Ignoring optional fields for update simplicity since they aren't in schema
            $existing->save();
            return back()->with('success', 'Existing feed stock updated successfully.');
        }

        // Set missing NOT NULL fields
        $validated['category_id'] = \App\Models\FeedCategory::firstOrCreate(
            ['name' => 'General Feed'],
            ['farm_id' => $farm->id, 'description' => 'Default Category']
        )->id;
        $validated['unit'] = 'kg';

        FeedInventory::create($validated);
        return back()->with('success', 'New feed inventory added successfully.');
    }

    public function update(Request $request, $id)
    {
        $item = FeedInventory::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'total_quantity'    => 'required|numeric|min:0',
            'minimum_threshold' => 'required|numeric|min:0',
        ]);

        $item->update($validated);
        return back()->with('success', 'Feed inventory updated successfully.');
    }

    public function destroy($id)
    {
        $item = FeedInventory::findOrFail($id);
        $name = $item->name;
        $item->delete();
        return back()->with('success', "'{$name}' has been removed from inventory.");
    }
}
