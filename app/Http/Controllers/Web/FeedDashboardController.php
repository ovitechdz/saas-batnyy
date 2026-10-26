<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedInventory;
use App\Models\Warehouse;
use App\Models\FeedingPlan;
use App\Services\AiNutritionService;

class FeedDashboardController extends Controller
{
    protected AiNutritionService $aiService;

    public function __construct(AiNutritionService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $inventory = FeedInventory::with('nutritionProfile')->get();
        $warehouses = Warehouse::all();
        $activePlans = FeedingPlan::where('is_active', true)->count();
        
        $stats = [
            'total_inventory_kg' => $inventory->sum('total_quantity'),
            'low_stock_items' => $inventory->where('total_quantity', '<=', 100)->count(),
            'active_feeding_plans' => $activePlans,
            'warehouse_utilization' => $warehouses->avg('current_utilization') ?? 0,
        ];

        // Generate AI Prediction for a target group
        $aiRecommendation = $this->aiService->generateAiRecommendation('Lactating Ewes', 65.5);
        $depletionAlerts = $this->aiService->optimizeCostsAndPredictDepletion();

        return view('feed.dashboard', compact('stats', 'inventory', 'warehouses', 'aiRecommendation', 'depletionAlerts'));
    }

    public function applyAiPlan(\Illuminate\Http\Request $request)
    {
        $farm = \App\Models\Farm::first();
        if (!$farm) {
            return back()->with('error', 'No farm configured.');
        }

        $category = \App\Models\FeedCategory::firstOrCreate(
            ['name' => 'AI Mixtures', 'farm_id' => $farm->id],
            ['description' => 'AI suggested feed mixtures']
        );

        // Create or get a feed inventory for the AI mix
        $inventory = \App\Models\FeedInventory::firstOrCreate(
            ['name' => 'AI Suggested Mix', 'farm_id' => $farm->id],
            [
                'category_id' => $category->id,
                'total_quantity' => 1000,
                'unit' => 'kg',
                'minimum_threshold' => 100,
            ]
        );

        FeedingPlan::create([
            'farm_id' => $farm->id,
            'name' => 'AI Suggested Mix - ' . date('Y-m-d'),
            'target_group' => 'Mixed Flock',
            'feed_inventory_id' => $inventory->id,
            'daily_ration_kg' => 2.5, // Default ration based on AI suggestion
            'schedule_time' => '08:00',
            'is_active' => true,
        ]);

        return back()->with('success', 'AI feeding plan applied successfully.');
    }
}
