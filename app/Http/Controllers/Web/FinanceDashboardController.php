<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AiFinancialService;
use App\Models\Invoice;
use App\Models\Account;

class FinanceDashboardController extends Controller
{
    protected AiFinancialService $aiFinance;

    public function __construct(AiFinancialService $aiFinance)
    {
        $this->aiFinance = $aiFinance;
    }

    public function index()
    {
        // Mock farm ID for demo context
        $farmId = 'e9ed396c-f1b7-49ae-8fca-507640ca6a27';
        
        $predictions = $this->aiFinance->predictCashFlow($farmId);
        $health = $this->aiFinance->calculateHealthScore($farmId);

        $stats = [
            'total_revenue' => 245000,
            'total_expenses' => 112000,
            'net_profit' => 133000,
            'accounts_receivable' => 15000,
            'accounts_payable' => 8500
        ];

        return view('finance.dashboard', compact('predictions', 'health', 'stats'));
    }
}
