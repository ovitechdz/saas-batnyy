<?php

$replacements = [
    // Sidebar
    '🌿 Platform Modules' => '🌿 {{ __("Platform Modules") }}',
    'label="Farm Operations"' => 'label="{{ __(\'Farm Operations\') }}"',
    'label="Farm Dashboard"' => 'label="{{ __(\'Farm Dashboard\') }}"',
    'label="Farms & Zones"' => 'label="{{ __(\'Farms & Zones\') }}"',
    'label="Barns & Capacity"' => 'label="{{ __(\'Barns & Capacity\') }}"',
    'label="Equipment & Fleet"' => 'label="{{ __(\'Equipment & Fleet\') }}"',
    'label="Water Management"' => 'label="{{ __(\'Water Management\') }}"',
    'label="Energy Management"' => 'label="{{ __(\'Energy Management\') }}"',
    'label="Livestock & Health"' => 'label="{{ __(\'Livestock & Health\') }}"',
    'label="Breeding & Genetics"' => 'label="{{ __(\'Breeding & Genetics\') }}"',
    'label="Logistics & Supply"' => 'label="{{ __(\'Logistics & Supply\') }}"',
    'label="Transportation"' => 'label="{{ __(\'Transportation\') }}"',
    'label="Customers"' => 'label="{{ __(\'Customers\') }}"',
    '⚡ Intelligence & Settings' => '⚡ {{ __("Intelligence & Settings") }}',
    'label="Smart Tech & AI"' => 'label="{{ __(\'Smart Tech & AI\') }}"',
    'label="IoT Sensors"' => 'label="{{ __(\'IoT Sensors\') }}"',
    'label="Enterprise"' => 'label="{{ __(\'Enterprise\') }}"',
    'label="Marketplace"' => 'label="{{ __(\'Marketplace\') }}"',
    'label="System"' => 'label="{{ __(\'System\') }}"',
    'label="Settings"' => 'label="{{ __(\'Settings\') }}"',
    'label="Admin"' => 'label="{{ __(\'Admin\') }}"',
    'System Administrator' => '{{ __(\'System Administrator\') }}',
    'Search animals, RFID, invoices...' => '{{ __(\'Search animals, RFID, invoices...\') }}',

    // Dashboard
    'Welcome back,' => '{{ __(\'Welcome back,\') }}',
    'AI Systems are operational. Your farm health is exceptional today.' => '{{ __(\'AI Systems are operational. Your farm health is exceptional today.\') }}',
    'Quick Action' => '{{ __(\'Quick Action\') }}',
    'Generate AI Report' => '{{ __(\'Generate AI Report\') }}',
    'AI Health Index' => '{{ __(\'AI Health Index\') }}',
    'Excellent Condition' => '{{ __(\'Excellent Condition\') }}',
    '>Total Livestock<' => '>{{ __(\'Total Livestock\') }}<',
    '>Growth Index<' => '>{{ __(\'Growth Index\') }}<',
    '>Breeding Success<' => '>{{ __(\'Breeding Success\') }}<',
    '>Pregnant<' => '>{{ __(\'Pregnant\') }}<',
    '>Cash Flow<' => '>{{ __(\'Cash Flow\') }}<',
    '>Revenue<' => '>{{ __(\'Revenue\') }}<',
    '>Solar Power<' => '>{{ __(\'Solar Power\') }}<',
    '>Storage<' => '>{{ __(\'Storage\') }}<',
    '>RFID Activity<' => '>{{ __(\'RFID Activity\') }}<',
    '>Online<' => '>{{ __(\'Online\') }}<',
    '>All Gates Active<' => '>{{ __(\'All Gates Active\') }}<',
    '>AI Recommendations<' => '>{{ __(\'AI Recommendations\') }}<',
    '>Financial & Growth Analytics<' => '>{{ __(\'Financial & Growth Analytics\') }}<',
    '>Revenue vs Expenses<' => '>{{ __(\'Revenue vs Expenses\') }}<',
    '>Average Weight Growth (kg)<' => '>{{ __(\'Average Weight Growth (kg)\') }}<',
    '>Resource Usage<' => '>{{ __(\'Resource Usage\') }}<',
];

$files = [
    __DIR__ . '/resources/views/layouts/app.blade.php',
    __DIR__ . '/resources/views/dashboard.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
}

echo "Files updated successfully.\n";
