<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Treatment;
use App\Models\MedicineUsage;
use App\Models\MedicineStock;
use App\Models\HealthAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VeterinaryService
{
    /**
     * Prescribe a treatment and log medicine usage.
     * Automatically checks stock and reduces inventory if applicable.
     */
    public function prescribeTreatment(array $treatmentData, array $medicines)
    {
        return DB::transaction(function () use ($treatmentData, $medicines) {
            $treatment = Treatment::create([
                'medical_record_id' => $treatmentData['medical_record_id'],
                'treatment_type' => $treatmentData['treatment_type'],
                'description' => $treatmentData['description'],
                'start_date' => $treatmentData['start_date'],
                'end_date' => $treatmentData['end_date'] ?? null,
                'status' => 'Ongoing'
            ]);

            foreach ($medicines as $med) {
                // Determine if we need to deduct stock
                $stock = null;
                if (!empty($med['stock_id'])) {
                    $stock = MedicineStock::findOrFail($med['stock_id']);
                    
                    if ($stock->quantity < $med['dosage']) {
                        throw new \Exception("Insufficient stock for medicine {$stock->medicine->name}");
                    }
                    
                    $stock->decrement('quantity', $med['dosage']);
                    
                    if ($stock->quantity <= 5) {
                        $this->triggerLowStockAlert($stock);
                    }
                }

                MedicineUsage::create([
                    'treatment_id' => $treatment->id,
                    'medicine_id' => $med['medicine_id'],
                    'stock_id' => $stock ? $stock->id : null,
                    'dosage' => $med['dosage'],
                    'unit' => $med['unit'],
                    'administered_at' => now(),
                    'administered_by' => auth()->id()
                ]);
            }

            return $treatment;
        });
    }

    private function triggerLowStockAlert(MedicineStock $stock)
    {
        Log::warning("Low stock alert for {$stock->medicine->name} (Batch: {$stock->batch_number}). Only {$stock->quantity} {$stock->unit} left.");
        // Could dispatch an event or email here
    }
    
    /**
     * Generates an AI Risk Score based on recorded symptoms
     */
    public function generateAiRiskScore(MedicalRecord $record): array
    {
        $score = 0;
        $riskLevel = 'Low';
        
        // Mocking AI Model Inference
        if ($record->temperature > 40.0) {
            $score += 35;
        }
        
        if ($record->heart_rate > 100) {
            $score += 20;
        }
        
        if ($score > 50) {
            $riskLevel = 'Critical';
        } elseif ($score > 25) {
            $riskLevel = 'Moderate';
        }
        
        return [
            'confidence_score' => rand(70, 99) / 100,
            'risk_level' => $riskLevel,
            'recommended_action' => $riskLevel === 'Critical' ? 'Immediate Isolation and Vet Consult' : 'Monitor for 24h'
        ];
    }
}
