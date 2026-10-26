<?php

$bladeFile = __DIR__ . '/resources/views/dashboard.blade.php';
if (file_exists($bladeFile)) {
    $content = file_get_contents($bladeFile);
    
    // Replace strings in dashboard.blade.php
    $replacements = [
        ">Task Center<" => ">{{ __('Task Center') }}<",
        "{{ \$task['title'] }}" => "{{ __(\$task['title']) }}",
        "{{ \$task['time'] }}" => "{{ __(\$task['time']) }}",
        ">High<" => ">{{ __('HIGH') }}<",
        "Add New Task\n" => "{{ __('Add New Task') }}\n",
        ">Interactive Farm Map<" => ">{{ __('Interactive Farm Map') }}<",
        "GPS Live Tracking\n" => "{{ __('GPS Live Tracking') }}\n",
        ">OVITECH TAWRIRA<" => ">{{ __('OVITECH TAWRIRA') }}<",
        "> Sidi Bel Abbes" => "> {{ __('Sidi Bel Abbes') }}",
        "> 12.66 Hectares" => "> {{ __('12.66 Hectares') }}",
        "> Blé Tendre" => "> {{ __('Blé Tendre') }}",
        ">UTM Coordinates<" => ">{{ __('UTM Coordinates') }}<",
        ">Section 01, Ilot 03<" => ">{{ __('Section 01, Ilot 03') }}<",
        "Real-time Animal Directory" => "{{ __('Real-time Animal Directory') }}",
        "Synchronized with RFID scanners 10s ago." => "{{ __('Synchronized with RFID scanners 10s ago.') }}",
        "placeholder=\"Search ID, Breed, RFID...\"" => "placeholder=\"{{ __('Search ID, Breed, RFID...') }}\""
    ];

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($bladeFile, $content);
}

$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'Task Center' => 'Centre de Tâches',
        'HIGH' => 'HAUT',
        'Morning Feeding' => 'Nourrissage du Matin',
        'Inspect Barn B' => 'Inspecter Grange B',
        'Vaccinate newborn lambs' => 'Vacciner les agneaux nouveau-nés',
        'Solar panel maintenance' => 'Maintenance des panneaux solaires',
        'Sync offline data' => 'Synchroniser les données hors ligne',
        '07:00 AM' => '07:00 AM',
        '10:30 AM' => '10:30 AM',
        '02:00 PM' => '14:00 PM',
        '04:00 PM' => '16:00 PM',
        '06:00 PM' => '18:00 PM',
        'Add New Task' => 'Ajouter une Nouvelle Tâche',
        'Interactive Farm Map' => 'Carte Interactive de la Ferme',
        'GPS Live Tracking' => 'Suivi GPS en Direct',
        'OVITECH TAWRIRA' => 'OVITECH TAWRIRA',
        'Sidi Bel Abbes' => 'Sidi Bel Abbès',
        '12.66 Hectares' => '12.66 Hectares',
        'Blé Tendre' => 'Blé Tendre',
        'UTM Coordinates' => 'Coordonnées UTM',
        'Section 01, Ilot 03' => 'Section 01, Ilot 03',
        'Real-time Animal Directory' => 'Annuaire des Animaux en Temps Réel',
        'Synchronized with RFID scanners 10s ago.' => 'Synchronisé avec les scanners RFID il y a 10s.',
        'Search ID, Breed, RFID...' => 'Rechercher ID, Race, RFID...'
    ],
    'ar' => [
        'Task Center' => 'مركز المهام',
        'HIGH' => 'هام جداً',
        'Morning Feeding' => 'التغذية الصباحية',
        'Inspect Barn B' => 'فحص الحظيرة ب',
        'Vaccinate newborn lambs' => 'تطعيم الحملان حديثي الولادة',
        'Solar panel maintenance' => 'صيانة الألواح الشمسية',
        'Sync offline data' => 'مزامنة البيانات غير المتصلة',
        '07:00 AM' => '07:00 صباحاً',
        '10:30 AM' => '10:30 صباحاً',
        '02:00 PM' => '02:00 مساءً',
        '04:00 PM' => '04:00 مساءً',
        '06:00 PM' => '06:00 مساءً',
        'Add New Task' => 'إضافة مهمة جديدة',
        'Interactive Farm Map' => 'خريطة المزرعة التفاعلية',
        'GPS Live Tracking' => 'تتبع GPS مباشر',
        'OVITECH TAWRIRA' => 'مزرعة أوفيتك - توريرة',
        'Sidi Bel Abbes' => 'سيدي بلعباس',
        '12.66 Hectares' => '12.66 هكتار',
        'Blé Tendre' => 'قمح طري (Blé Tendre)',
        'UTM Coordinates' => 'إحداثيات UTM',
        'Section 01, Ilot 03' => 'القسم 01، الجزيرة 03',
        'Real-time Animal Directory' => 'دليل الحيوانات الفوري',
        'Synchronized with RFID scanners 10s ago.' => 'تمت المزامنة مع أجهزة RFID قبل 10 ثوانٍ.',
        'Search ID, Breed, RFID...' => 'ابحث عن المعرف، السلالة، RFID...'
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "Final fixes applied.\n";
