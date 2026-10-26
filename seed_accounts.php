<?php

$farmId = 'e9ed396c-f1b7-49ae-8fca-507640ca6a27';
$currencyId = \Illuminate\Support\Facades\DB::table('currencies')->value('id');

if (!$currencyId) {
    \Illuminate\Support\Facades\DB::table('currencies')->insert([
        'id' => \Illuminate\Support\Str::uuid(),
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $currencyId = \Illuminate\Support\Facades\DB::table('currencies')->where('code', 'USD')->value('id');
}

$accounts = [
    ['name' => 'Cash', 'account_type' => 'asset', 'balance' => 0],
    ['name' => 'Bank Account', 'account_type' => 'asset', 'balance' => 0],
    ['name' => 'Accounts Receivable', 'account_type' => 'asset', 'balance' => 0],
    ['name' => 'Accounts Payable', 'account_type' => 'liability', 'balance' => 0],
    ['name' => 'Sales Revenue', 'account_type' => 'revenue', 'balance' => 0],
    ['name' => 'Feed Expenses', 'account_type' => 'expense', 'balance' => 0],
    ['name' => 'Veterinary Expenses', 'account_type' => 'expense', 'balance' => 0],
    ['name' => 'Equipment Purchases', 'account_type' => 'asset', 'balance' => 0],
];

foreach ($accounts as $acc) {
    $existing = \Illuminate\Support\Facades\DB::table('accounts')->where('farm_id', $farmId)->where('name', $acc['name'])->first();
    if (!$existing) {
        \Illuminate\Support\Facades\DB::table('accounts')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'farm_id' => $farmId,
            'name' => $acc['name'],
            'account_type' => $acc['account_type'],
            'balance' => $acc['balance'],
            'currency_id' => $currencyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

echo "Accounts seeded correctly.\n";
