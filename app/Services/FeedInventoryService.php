<?php

namespace App\Services;

use App\Models\FeedInventory;
use App\Models\FeedConsumption;
use App\Models\FeedingPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedInventoryService
{
    /**
     * Executes the daily feeding plan and deducts inventory.
     */
    public function executeDailyFeedingPlan()
    {
        $plans = FeedingPlan::where('is_active', true)->get();
        
        DB::transaction(function () use ($plans) {
            foreach ($plans as $plan) {
                // Here you would normally calculate the exact number of animals in the target group
                // For demonstration, we assume 100 animals in a group
                $animalCount = 100;
                $totalQuantityNeeded = $plan->daily_ration_kg * $animalCount;
                
                $inventory = FeedInventory::findOrFail($plan->feed_inventory_id);
                
                if ($inventory->total_quantity < $totalQuantityNeeded) {
                    Log::error("Failed to execute feeding plan {$plan->name}: Insufficient inventory for {$inventory->name}. Need {$totalQuantityNeeded}kg, have {$inventory->total_quantity}kg.");
                    $this->triggerOutOfStockAlert($inventory);
                    continue;
                }
                
                // Deduct inventory
                $inventory->decrement('total_quantity', $totalQuantityNeeded);
                
                // Log consumption
                FeedConsumption::create([
                    'farm_id' => $plan->farm_id,
                    'feed_batch_id' => $inventory->id, // Simplified for mock
                    'quantity_consumed' => $totalQuantityNeeded,
                    'fed_at' => now(),
                    'fed_by' => null // System
                ]);
                
                if ($inventory->total_quantity <= $inventory->minimum_threshold) {
                    $this->triggerLowStockAlert($inventory);
                }
            }
        });
    }

    private function triggerLowStockAlert(FeedInventory $inventory)
    {
        Log::warning("Low feed stock alert for {$inventory->name}. Only {$inventory->total_quantity} {$inventory->unit} left.");
    }
    
    private function triggerOutOfStockAlert(FeedInventory $inventory)
    {
        Log::critical("OUT OF STOCK alert for {$inventory->name}!");
    }
}
