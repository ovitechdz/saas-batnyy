<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Budget;
use App\Models\FarmAsset;

class FinanceApiController extends Controller
{
    public function getAccounts()
    {
        return response()->json(Account::paginate(20));
    }

    public function getJournalEntries()
    {
        return response()->json(JournalEntry::with('lines')->paginate(20));
    }

    public function getBudgets()
    {
        return response()->json(Budget::paginate(20));
    }

    public function getAssets()
    {
        return response()->json(FarmAsset::paginate(20));
    }
}
