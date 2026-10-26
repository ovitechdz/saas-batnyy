<?php

namespace App\Services;

use App\Models\FeedInventory;
use App\Models\FeedingPlan;
use Illuminate\Support\Facades\Log;

class AiNutritionService
{
    /**
     * AI calculates optimal Feed Conversion Ratio (FCR)
     * and recommends formulation adjustments based on cost.
     */
    public function generateAiRecommendation(string $targetGroup, float $avgWeight)
    {
        $confidenceScore = rand(75, 98) / 100;
        
        // Mock AI Logic based on target group and weight
        $recommendedProteins = 16.0; // percentage
        $recommendedEnergy = 2.4; // Mcal/kg

        if ($targetGroup === 'Lactating Ewes') {
            $recommendedProteins = 18.5;
            $recommendedEnergy = 2.8;
        }

        if ($targetGroup === 'Lambs' && $avgWeight < 20) {
            $recommendedProteins = 20.0;
            $recommendedEnergy = 3.0;
        }

        // Check available inventory to suggest a mix
        $availableFeed = FeedInventory::with('nutritionProfile')->get();
        $expectedCostPerKg = 0.45; // USD
        
        return [
            'recommended_formula' => [
                'protein_percentage' => $recommendedProteins,
                'energy_mcal' => $recommendedEnergy,
            ],
            'estimated_cost_per_kg' => $expectedCostPerKg,
            'expected_feed_efficiency' => '1.2 kg feed / 1 kg gain',
            'confidence_score' => $confidenceScore,
            'suggested_mix' => [
                'Alfalfa Hay' => '60%',
                'Corn' => '25%',
                'Soybean Meal' => '12%',
                'Mineral Premix' => '3%'
            ]
        ];
    }
    
    /**
     * Predict future costs and alert on stock depletion
     */
    public function optimizeCostsAndPredictDepletion()
    {
        $depletingSoon = [];
        $inventory = FeedInventory::all();
        
        foreach ($inventory as $feed) {
            // Assume an average daily consumption of 5% of total stock for mock purposes
            $dailyConsumptionRate = rand(1, 10) / 100 * $feed->total_quantity;
            
            if ($dailyConsumptionRate > 0) {
                $daysRemaining = round($feed->total_quantity / $dailyConsumptionRate);
                if ($daysRemaining <= 7) {
                    $depletingSoon[] = [
                        'feed' => $feed->name,
                        'days_remaining' => $daysRemaining,
                        'optimal_purchase_date' => now()->addDays(max(0, $daysRemaining - 3))->toDateString()
                    ];
                }
            }
        }
        
        return $depletingSoon;
    }
}
