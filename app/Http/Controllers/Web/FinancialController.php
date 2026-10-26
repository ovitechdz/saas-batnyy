<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        $farm_id = auth()->user()->farm_id;
        
        $transactions = Transaction::where('farm_id', $farm_id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('financial.index', compact('transactions', 'totalIncome', 'totalExpense', 'netProfit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['farm_id'] = auth()->user()->farm_id;

        Transaction::create($validated);

        return redirect()->route('financial.index')->with('success', 'Transaction added successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('farm_id', auth()->user()->farm_id)->findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
