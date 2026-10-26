<?php

$files = [
    __DIR__ . '/resources/views/layouts/app.blade.php',
    __DIR__ . '/resources/views/dashboard.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // app.blade.php specific
    $content = str_replace(">FARM<", ">{{ __('FARM') }}<", $content);
    $content = str_replace(">Smart Agriculture Platform<", ">{{ __('Smart Agriculture Platform') }}<", $content);
    $content = str_replace("x-text=\"offline ? 'Offline' : 'Online'\"", "x-text=\"offline ? '{{ __('Offline') }}' : '{{ __('Online') }}'\"", $content);
    $content = preg_replace('/<i data-lucide="zap".*?<\/i>\s*Quick\s*<\/button>/s', '<i data-lucide="zap" class="w-3.5 h-3.5"></i>{{ __(\'Quick\') }}</button>', $content);

    // dashboard.blade.php specific
    $content = str_replace("{{ auth()->user()->farm->name ?? 'Ovitech' }} Farm", "{{ auth()->user()->farm->name ?? 'Ovitech' }} {{ __('Farm') }}", $content);
    $content = str_replace("Morning • {{ \$weather['temp'] }}°C • {{ \$weather['humidity'] }} Humidity", "{{ __('Morning') }} • {{ \$weather['temp'] }}°C • {{ \$weather['humidity'] }} {{ __('Humidity') }}", $content);
    
    $content = preg_replace('/>RFID\s*Activity</s', '>{{ __(\'RFID Activity\') }}<', $content);

    // AI Prediction text coming from controller
    $content = str_replace("{{ collect(\$aiInsights['predictions'])->firstWhere('priority', 'high')['text'] ?? 'No critical alerts.' }}", "{{ __(collect(\$aiInsights['predictions'])->firstWhere('priority', 'high')['text'] ?? 'No critical alerts.') }}", $content);

    file_put_contents($file, $content);
}

echo "Secondary files updated successfully.\n";

// Update JSON files
$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'FARM' => 'FERME',
        'Smart Agriculture Platform' => 'Plateforme Agricole Intelligente',
        'Offline' => 'Hors Ligne',
        'Online' => 'En Ligne',
        'Quick' => 'Rapide',
        'Farm' => 'Ferme',
        'Morning' => 'Matin',
        'Humidity' => 'Humidité',
        'RFID Activity' => 'Activité RFID',
        'High risk of foot rot in Barn 3 due to humidity.' => 'Risque élevé de piétin dans la grange 3 en raison de l\'humidité.',
        'High risk of foot rot in Barn 3 due to' => 'Risque élevé de piétin dans la grange 3 en raison de',
        'No critical alerts.' => 'Aucune alerte critique.'
    ],
    'ar' => [
        'FARM' => 'مزرعة',
        'Smart Agriculture Platform' => 'منصة الزراعة الذكية',
        'Offline' => 'غير متصل',
        'Online' => 'متصل',
        'Quick' => 'سريع',
        'Farm' => 'مزرعة',
        'Morning' => 'الصباح',
        'Humidity' => 'الرطوبة',
        'RFID Activity' => 'نشاط RFID',
        'High risk of foot rot in Barn 3 due to humidity.' => 'خطر كبير للإصابة بتعفن القدم في الحظيرة 3 بسبب الرطوبة.',
        'High risk of foot rot in Barn 3 due to' => 'خطر كبير للإصابة بتعفن القدم في الحظيرة 3 بسبب',
        'No critical alerts.' => 'لا توجد تنبيهات حرجة.'
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "JSON updated successfully.\n";
