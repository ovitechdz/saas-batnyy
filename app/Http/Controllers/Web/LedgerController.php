<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Account;
use App\Services\GeneralLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LedgerController extends Controller
{
    protected GeneralLedgerService $glService;

    public function __construct(GeneralLedgerService $glService)
    {
        $this->glService = $glService;
    }

    public function index()
    {
        // Mock farm ID for demo context
        $farmId = 'e9ed396c-f1b7-49ae-8fca-507640ca6a27';
        
        $trialBalance = $this->glService->generateTrialBalance($farmId);
        $recentJournals = JournalEntry::with('lines.account', 'lines.costCenter')
            ->where('farm_id', $farmId)
            ->orderBy('entry_date', 'desc')
            ->limit(20)
            ->get();

        $accounts = Account::where('farm_id', $farmId)->orderBy('name')->get();

        return view('finance.ledger', compact('trialBalance', 'recentJournals', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string|max:255',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ]);

        $farmId = 'e9ed396c-f1b7-49ae-8fca-507640ca6a27'; // Mock farm ID

        // Calculate totals
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($request->lines as $line) {
            $totalDebit += (float) ($line['debit'] ?? 0);
            $totalCredit += (float) ($line['credit'] ?? 0);
        }

        // Validate balance (allow slight floating point inaccuracies up to 2 decimals)
        if (abs($totalDebit - $totalCredit) > 0.01 || $totalDebit == 0) {
            return back()->with('error', 'Journal Entry must be balanced (Total Debits = Total Credits) and greater than zero.');
        }

        try {
            DB::beginTransaction();

            $entry = JournalEntry::create([
                'id' => Str::uuid(),
                'farm_id' => $farmId,
                'reference_number' => 'JRN-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'entry_date' => $request->entry_date,
                'description' => $request->description,
                'status' => 'posted',
            ]);

            foreach ($request->lines as $line) {
                $debit = (float) ($line['debit'] ?? 0);
                $credit = (float) ($line['credit'] ?? 0);

                if ($debit == 0 && $credit == 0) continue;

                JournalLine::create([
                    'id' => Str::uuid(),
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $debit,
                    'credit' => $credit,
                    'description' => $request->description,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Journal Entry posted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while posting the journal entry: ' . $e->getMessage());
        }
    }
}
