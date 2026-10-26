<?php

$bladeFile = __DIR__ . '/resources/views/dashboard.blade.php';
if (file_exists($bladeFile)) {
    $content = file_get_contents($bladeFile);
    
    // Replace strings in dashboard.blade.php
    $replacements = [
        ">Animal Profile<" => ">{{ __('Animal Profile') }}<",
        ">RFID Tag<" => ">{{ __('RFID Tag') }}<",
        ">Health Status<" => ">{{ __('Health Status') }}<",
        ">Metrics<" => ">{{ __('Metrics') }}<",
        ">AI Score<" => ">{{ __('AI Score') }}<",
        ">Actions<" => ">{{ __('Actions') }}<",
        "{{ \$animal->name }}" => "{{ __(\$animal->name) }}",
        "{{ \$animal->gender }} • {{ \$animal->breed }} • {{ \$animal->age }}" => "{{ __(\$animal->gender) }} • {{ __(\$animal->breed) }} • {{ __(\$animal->age) }}",
        "{{ \$animal->location }}" => "{{ __(\$animal->location) }}",
        ">Healthy<" => ">{{ __('HEALTHY') }}<",
        ">Sick<" => ">{{ __('SICK') }}<",
        "{{ \$animal->weight_kg }} kg<" => "{{ \$animal->weight_kg }} {{ __('kg') }}<",
        "Last scan: {{ \$animal->last_scan }}" => "{{ __('Last scan:') }} {{ __(\$animal->last_scan) }}",
    ];

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($bladeFile, $content);
}

$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'Animal Profile' => 'Profil de l\'Animal',
        'RFID Tag' => 'Étiquette RFID',
        'Health Status' => 'État de Santé',
        'Metrics' => 'Métriques',
        'AI Score' => 'Score IA',
        'Actions' => 'Actions',
        'Ewe 101' => 'Brebis 101',
        'Ram 042' => 'Bélier 042',
        'Lamb Alpha' => 'Agneau Alpha',
        'Ewe 204' => 'Brebis 204',
        'F' => 'F',
        'M' => 'M',
        'Merino' => 'Mérinos',
        'Suffolk' => 'Suffolk',
        'Dorper' => 'Dorper',
        '2 yrs' => '2 ans',
        '3 yrs' => '3 ans',
        '4 yrs' => '4 ans',
        '2 mo' => '2 mois',
        'Barn A' => 'Grange A',
        'Barn B' => 'Grange B',
        'Vet Room' => 'Salle Vétérinaire',
        'HEALTHY' => 'SAIN',
        'SICK' => 'MALADE',
        'kg' => 'kg',
        'Last scan:' => 'Dernier scan:',
        '10m ago' => 'il y a 10 min',
        '1h ago' => 'il y a 1h',
        '5m ago' => 'il y a 5 min',
        '2h ago' => 'il y a 2h',
        'Always learning' => 'Apprentissage continu'
    ],
    'ar' => [
        'Animal Profile' => 'ملف الحيوان',
        'RFID Tag' => 'علامة RFID',
        'Health Status' => 'الحالة الصحية',
        'Metrics' => 'المقاييس',
        'AI Score' => 'تقييم الذكاء الاصطناعي',
        'Actions' => 'إجراءات',
        'Ewe 101' => 'نعجة 101',
        'Ram 042' => 'كبش 042',
        'Lamb Alpha' => 'حمل ألفا',
        'Ewe 204' => 'نعجة 204',
        'F' => 'أنثى',
        'M' => 'ذكر',
        'Merino' => 'مرينو',
        'Suffolk' => 'سوفولك',
        'Dorper' => 'دوربر',
        '2 yrs' => 'سنتان',
        '3 yrs' => '3 سنوات',
        '4 yrs' => '4 سنوات',
        '2 mo' => 'شهران',
        'Barn A' => 'حظيرة أ',
        'Barn B' => 'حظيرة ب',
        'Vet Room' => 'العيادة البيطرية',
        'HEALTHY' => 'سليم',
        'SICK' => 'مريض',
        'kg' => 'كجم',
        'Last scan:' => 'آخر مسح:',
        '10m ago' => 'منذ 10 د',
        '1h ago' => 'منذ ساعة',
        '5m ago' => 'منذ 5 د',
        '2h ago' => 'منذ ساعتين',
        'Always learning' => 'دائم التعلم'
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "Final fixes applied for table.\n";
