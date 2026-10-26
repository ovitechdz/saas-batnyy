<?php

// 1. DashboardController.php translation wrapper
$controllerFile = __DIR__ . '/app/Http/Controllers/Web/DashboardController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Wrap months in controller array
    $content = str_replace(
        "['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']",
        "[__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec')]",
        $content
    );

    file_put_contents($controllerFile, $content);
}

// 2. dashboard.blade.php replacements
$bladeFile = __DIR__ . '/resources/views/dashboard.blade.php';
if (file_exists($bladeFile)) {
    $content = file_get_contents($bladeFile);
    
    $replacements = [
        "{{ \$prediction['type'] }} Prediction" => "{{ __(\$prediction['type'] . ' Prediction') }}",
        "{{ \$prediction['confidence'] }}% Conf." => "{{ \$prediction['confidence'] }}% {{ __('Conf.') }}",
        ">Suggested Action<" => ">{{ __('Suggested Action') }}<",
        "Impact: {{ \$prediction['impact'] }}" => "{{ __('Impact:') }} {{ __(\$prediction['impact']) }}",
        "{{ \$prediction['action'] }}" => "{{ __(\$prediction['action']) }}",
        ">1W<" => ">{{ __('1W') }}<",
        ">1M<" => ">{{ __('1M') }}<",
        ">1Y<" => ">{{ __('1Y') }}<",
        "label: 'Revenue ($)'" => "label: '{{ __('Revenue ($)') }}'",
        "label: 'Expenses ($)'" => "label: '{{ __('Expenses ($)') }}'",
        "label: 'Solar kW'" => "label: '{{ __('Solar kW') }}'",
        "label: 'Water L'" => "label: '{{ __('Water L') }}'",
        "label: 'Avg Weight (kg)'" => "label: '{{ __('Avg Weight (kg)') }}'",
        ">OviTech Intelligence<" => ">{{ __('OviTech Intelligence') }}<",
        ">Always learning<" => ">{{ __('Always learning') }}<",
        ">Today<" => ">{{ __('Today') }}<",
        ">Good afternoon! I noticed that feed consumption in Barn A is 10% lower than predicted. Would you like me to schedule a veterinary check?<" => ">{{ __('Good afternoon! I noticed that feed consumption in Barn A is 10% lower than predicted. Would you like me to schedule a veterinary check?') }}<",
        ">Yes, please schedule it for tomorrow morning.<" => ">{{ __('Yes, please schedule it for tomorrow morning.') }}<",
        ">Task Created<" => ">{{ __('Task Created') }}<",
        ">Veterinary check for Barn A scheduled.<" => ">{{ __('Veterinary check for Barn A scheduled.') }}<",
        ">View Task<" => ">{{ __('View Task') }}<",
        "placeholder=\"Ask AI...\"" => "placeholder=\"{{ __('Ask AI...') }}\"",
        ">OviTech AI<" => ">{{ __('OviTech AI') }}<",
    ];

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($bladeFile, $content);
}

// 3. Update JSON files
$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'Disease Prediction' => 'Prédiction des Maladies',
        'Weight Prediction' => 'Prédiction de Poids',
        'Breeding Prediction' => 'Prédiction de Reproduction',
        'Conf.' => 'Conf.',
        'Suggested Action' => 'Action Suggérée',
        'Impact:' => 'Impact:',
        '1W' => '1S',
        '1M' => '1M',
        '1Y' => '1A',
        'Revenue ($)' => 'Revenus ($)',
        'Expenses ($)' => 'Dépenses ($)',
        'Solar kW' => 'Solaire kW',
        'Water L' => 'Eau L',
        'Avg Weight (kg)' => 'Poids Moyen (kg)',
        'OviTech Intelligence' => 'Intelligence OviTech',
        'Always learning' => 'Apprentissage continu',
        'Today' => 'Aujourd\'hui',
        'Good afternoon! I noticed that feed consumption in Barn A is 10% lower than predicted. Would you like me to schedule a veterinary check?' => 'Bonjour! J\'ai remarqué que la consommation dans la grange A est 10% inférieure aux prévisions. Voulez-vous planifier un contrôle?',
        'Yes, please schedule it for tomorrow morning.' => 'Oui, veuillez le planifier pour demain matin.',
        'Task Created' => 'Tâche Créée',
        'Veterinary check for Barn A scheduled.' => 'Contrôle vétérinaire pour la grange A planifié.',
        'View Task' => 'Voir la Tâche',
        'Ask AI...' => 'Demander à l\'IA...',
        'OviTech AI' => 'IA OviTech',
        'Jan' => 'Jan', 'Feb' => 'Fév', 'Mar' => 'Mar', 'Apr' => 'Avr', 'May' => 'Mai', 'Jun' => 'Juin',
        'Jul' => 'Juil', 'Aug' => 'Août', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Déc',
        'Switch to Starter Formula X2' => 'Passer à la formule de démarrage X2',
        'Introduce Ram 042' => 'Introduire Bélier 042',
        'Prevents 15 potential infections' => 'Prévient 15 infections potentielles',
        '+2.5kg avg weight gain in 14 days' => '+2.5kg de gain moyen en 14 jours',
        '85% conception probability' => '85% de probabilité de conception',
        'Schedule hoof trimming and dry bedding' => 'Planifier un parage et une litière sèche',
        'Lamb group Alpha slightly underweight.' => 'Groupe d\'agneaux Alpha légèrement en sous-poids.',
        'Ewes entering optimal fertility window 12.' => 'Brebis entrant dans la fenêtre de fertilité optimale 12.',
    ],
    'ar' => [
        'Disease Prediction' => 'توقع الأمراض',
        'Weight Prediction' => 'توقع الوزن',
        'Breeding Prediction' => 'توقع التكاثر',
        'Conf.' => 'ثقة',
        'Suggested Action' => 'الإجراء المقترح',
        'Impact:' => 'التأثير:',
        '1W' => 'أسبوع',
        '1M' => 'شهر',
        '1Y' => 'سنة',
        'Revenue ($)' => 'الإيرادات ($)',
        'Expenses ($)' => 'المصروفات ($)',
        'Solar kW' => 'الطاقة الشمسية (kW)',
        'Water L' => 'المياه (L)',
        'Avg Weight (kg)' => 'متوسط الوزن (كجم)',
        'OviTech Intelligence' => 'ذكاء أوفيتك',
        'Always learning' => 'دائم التعلم',
        'Today' => 'اليوم',
        'Good afternoon! I noticed that feed consumption in Barn A is 10% lower than predicted. Would you like me to schedule a veterinary check?' => 'مساء الخير! لاحظت أن استهلاك العلف في الحظيرة أ أقل بنسبة 10٪ من المتوقع. هل ترغب في جدولة فحص بيطري؟',
        'Yes, please schedule it for tomorrow morning.' => 'نعم، يرجى جدولته لصباح الغد.',
        'Task Created' => 'تم إنشاء المهمة',
        'Veterinary check for Barn A scheduled.' => 'تمت جدولة الفحص البيطري للحظيرة أ.',
        'View Task' => 'عرض المهمة',
        'Ask AI...' => 'اسأل الذكاء الاصطناعي...',
        'OviTech AI' => 'ذكاء أوفيتك',
        'Jan' => 'يناير', 'Feb' => 'فبراير', 'Mar' => 'مارس', 'Apr' => 'أبريل', 'May' => 'مايو', 'Jun' => 'يونيو',
        'Jul' => 'يوليو', 'Aug' => 'أغسطس', 'Sep' => 'سبتمبر', 'Oct' => 'أكتوبر', 'Nov' => 'نوفمبر', 'Dec' => 'ديسمبر',
        'Switch to Starter Formula X2' => 'التبديل إلى تركيبة البداية X2',
        'Introduce Ram 042' => 'إدخال الكبش 042',
        'Prevents 15 potential infections' => 'يمنع 15 إصابة محتملة',
        '+2.5kg avg weight gain in 14 days' => '+2.5 كجم زيادة متوسطة في الوزن خلال 14 يوماً',
        '85% conception probability' => 'احتمالية الحمل 85%',
        'Schedule hoof trimming and dry bedding' => 'جدولة تقليم الحوافر وتوفير فراش جاف',
        'Lamb group Alpha slightly underweight.' => 'مجموعة الحملان ألفا تعاني من نقص طفيف في الوزن.',
        'Ewes entering optimal fertility window 12.' => 'النعاج تدخل نافذة الخصوبة المثالية 12.',
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "Final translation batch applied successfully.\n";
