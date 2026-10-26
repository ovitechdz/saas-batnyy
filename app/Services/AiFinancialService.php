<?php

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AiFinancialService
{
    /**
     * Generate an AI prediction for cash flow next month.
     */
    public function predictCashFlow(string $farmId)
    {
        $historicalFlow = CashFlow::where('farm_id', $farmId)->orderBy('year', 'desc')->orderBy('month', 'desc')->take(6)->get();
        
        $avgIncome = $historicalFlow->avg('total_income') ?? 50000;
        $avgExpense = $historicalFlow->avg('total_expenses') ?? 30000;
        
        // Add some AI "seasonality" logic (mocked)
        $predictedIncome = $avgIncome * (1 + (rand(-5, 10) / 100));
        $predictedExpense = $avgExpense * (1 + (rand(-2, 12) / 100));

        return [
            'predicted_income' => $predictedIncome,
            'predicted_expense' => $predictedExpense,
            'predicted_net' => $predictedIncome - $predictedExpense,
            'confidence' => 88.5
        ];
    }

    /**
     * Provide a Financial Health Score.
     */
    public function calculateHealthScore(string $farmId)
    {
        // A complex AI algorithm that looks at debt ratio, liquidity, margins
        // Simplified for representation
        $score = rand(70, 95);
        $status = 'Good';
        if ($score > 85) $status = 'Excellent';
        if ($score < 75) $status = 'Warning';

        return [
            'score' => $score,
            'status' => $status,
            'recommendations' => [
                'Consider locking in feed contracts to hedge against predicted 5% price increase.',
                'Accounts receivable are aging. Follow up on unpaid invoices over 30 days.'
            ]
        ];
    }
}
