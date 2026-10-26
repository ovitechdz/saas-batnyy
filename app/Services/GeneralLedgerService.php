<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class GeneralLedgerService
{
    /**
     * Post a Journal Entry ensuring double-entry principles (Debits = Credits).
     */
    public function postJournalEntry(string $farmId, array $lines, string $description, string $date)
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $totalDebit += $line['debit'] ?? 0;
            $totalCredit += $line['credit'] ?? 0;
        }

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new Exception("Journal Entry unbalanced. Debits ({$totalDebit}) do not equal Credits ({$totalCredit}).");
        }

        DB::transaction(function () use ($farmId, $lines, $description, $date) {
            $referenceNumber = 'JE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $entry = JournalEntry::create([
                'farm_id' => $farmId,
                'reference_number' => $referenceNumber,
                'entry_date' => $date,
                'description' => $description,
                'status' => 'posted'
            ]);

            foreach ($lines as $line) {
                JournalLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'cost_center_id' => $line['cost_center_id'] ?? null,
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null
                ]);

                // Update account balances
                $account = Account::findOrFail($line['account_id']);
                // Assuming standard accounting: Asset/Expense increase with Debit, Liability/Equity/Revenue increase with Credit.
                // For simplicity in a general model, we'll assume the 'balance' reflects the natural balance.
                // Let's implement a safe generic balance update depending on account type.
                
                $amount = ($line['debit'] ?? 0) - ($line['credit'] ?? 0);
                if (in_array($account->account_type, ['Liability', 'Equity', 'Revenue'])) {
                    $amount = ($line['credit'] ?? 0) - ($line['debit'] ?? 0);
                }
                
                $account->increment('balance', $amount);
            }
        });

        return true;
    }

    /**
     * Generate Trial Balance.
     */
    public function generateTrialBalance(string $farmId)
    {
        $accounts = Account::where('farm_id', $farmId)->get();
        $trialBalance = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $balance = $account->balance;
            if ($balance > 0) {
                if (in_array($account->account_type, ['Asset', 'Expense'])) {
                    $trialBalance[] = ['account' => $account->name, 'debit' => $balance, 'credit' => 0];
                    $totalDebit += $balance;
                } else {
                    $trialBalance[] = ['account' => $account->name, 'debit' => 0, 'credit' => $balance];
                    $totalCredit += $balance;
                }
            } elseif ($balance < 0) {
                if (in_array($account->account_type, ['Asset', 'Expense'])) {
                    $trialBalance[] = ['account' => $account->name, 'debit' => 0, 'credit' => abs($balance)];
                    $totalCredit += abs($balance);
                } else {
                    $trialBalance[] = ['account' => $account->name, 'debit' => abs($balance), 'credit' => 0];
                    $totalDebit += abs($balance);
                }
            }
        }

        return [
            'entries' => $trialBalance,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'is_balanced' => round($totalDebit, 2) === round($totalCredit, 2)
        ];
    }
}
