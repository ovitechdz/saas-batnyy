<?php
$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'MAIN OVITECH FARM FARM' => 'FERME PRINCIPALE OVITECH',
        'MAIN OVITECH FARM' => 'FERME PRINCIPALE OVITECH'
    ],
    'ar' => [
        'MAIN OVITECH FARM FARM' => 'مزرعة أوفيتك الرئيسية',
        'MAIN OVITECH FARM' => 'مزرعة أوفيتك الرئيسية'
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "JSON updated successfully.\n";
