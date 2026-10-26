<?php

namespace App\Http\Controllers\Web;

use App\DTO\AnimalData;
use App\Http\Controllers\Controller;
use App\Services\LivestockService;
use Illuminate\Http\Request;

class LivestockController extends Controller
{
    public function __construct(
        protected LivestockService $livestockService
    ) {}

    public function index(Request $request)
    {
        $farmId = $request->user()->farm_id ?? \App\Models\Farm::first()->id ?? null;
        if (!$farmId) {
            return redirect()->route('dashboard')->with('error', 'No farm associated with your account.');
        }

        $filters = $request->only(['search', 'status_id', 'breed_id', 'gender']);
        
        $livestock = $this->livestockService->getFarmLivestock($farmId, $filters, 50);
        
        // Load lookups for filters
        $statuses = \App\Models\AnimalStatus::all();
        $breeds = \App\Models\AnimalBreed::all();

        return view('livestock.index', compact('livestock', 'filters', 'statuses', 'breeds'));
    }

    public function create()
    {
        $statuses = \App\Models\AnimalStatus::all();
        $breeds = \App\Models\AnimalBreed::all();
        $categories = \App\Models\AnimalCategory::all();
        $species = \App\Models\AnimalSpecies::all();
        $barns = \App\Models\FarmBarn::where('farm_id', auth()->user()->farm_id ?? \App\Models\Farm::first()->id)->get();

        return view('livestock.create', compact('statuses', 'breeds', 'categories', 'species', 'barns'));
    }

    public function store(Request $request)
    {
        $data = AnimalData::fromRequest($request);
        
        try {
            $animal = $this->livestockService->registerAnimal($data, $request->user()->id);
            return redirect()->route('livestock.show', $animal->id)->with('success', 'Animal registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to register animal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $animal = $this->livestockService->getAnimalDetails($id);
        if (!$animal) {
            abort(404, 'Animal not found');
        }

        return view('livestock.show', compact('animal'));
    }

    public function uploadPhoto(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|max:5120',
        ]);

        $animal = \App\Models\Animal::findOrFail($id);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('livestock_photos', 'public');
            
            $animal->photos()->update(['is_primary' => false]);
            
            $animal->photos()->create([
                'photo_path' => $path,
                'is_primary' => true,
            ]);
        }

        return back()->with('success', __('Photo updated successfully.'));
    }

    public function edit($id)
    {
        $animal = $this->livestockService->getAnimalDetails($id);
        if (!$animal) abort(404);

        $statuses = \App\Models\AnimalStatus::all();
        $breeds = \App\Models\AnimalBreed::all();
        $categories = \App\Models\AnimalCategory::all();
        $species = \App\Models\AnimalSpecies::all();
        $barns = \App\Models\FarmBarn::where('farm_id', $animal->farm_id)->get();

        return view('livestock.edit', compact('animal', 'statuses', 'breeds', 'categories', 'species', 'barns'));
    }

    public function update(Request $request, $id)
    {
        $data = AnimalData::fromRequest($request);
        
        try {
            $this->livestockService->updateAnimal($id, $data, $request->user()->id);
            return redirect()->route('livestock.show', $id)->with('success', 'Animal profile updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update animal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->livestockService->removeAnimal($id);
        return redirect()->route('livestock.index')->with('success', 'Animal has been removed from the system.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $animalIds = explode(',', $request->input('animal_ids', ''));
        $animalIds = array_filter($animalIds);

        if (empty($animalIds)) {
            return back()->with('error', 'No animals selected.');
        }

        try {
            $result = $this->livestockService->processBulkAction($action, $animalIds, $request->all(), $request->user()->id);
            return back()->with('success', "Bulk action completed successfully on {$result['count']} animals.");
        } catch (\Exception $e) {
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}
