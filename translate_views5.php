<?php

$bladeFile = __DIR__ . '/resources/views/dashboard.blade.php';
if (file_exists($bladeFile)) {
    $content = file_get_contents($bladeFile);
    
    // Add missing translation wrapper for the prediction text
    $content = str_replace("{{ \$prediction['text'] }}", "{{ __(\$prediction['text']) }}", $content);
    
    file_put_contents($bladeFile, $content);
}

$langs = ['fr', 'ar'];
$newTranslations = [
    'fr' => [
        'Schedule hoof trimming and dry bedding.' => 'Planifier un parage et une litière sèche.',
        'Prevents 15 potential infections.' => 'Prévient 15 infections potentielles.',
        'Switch to Starter Formula X2.' => 'Passer à la formule de démarrage X2.',
        '+2.5kg avg weight gain in 14 days.' => '+2.5kg de gain moyen en 14 jours.',
        'Introduce Ram 042.' => 'Introduire Bélier 042.',
        '85% conception probability.' => '85% de probabilité de conception.',
        '12 Ewes entering optimal fertility window.' => '12 brebis entrant dans la fenêtre de fertilité optimale.',
        'Financial Prediction' => 'Prédiction Financière',
        'Feed prices predicted to rise by 8% next month.' => 'Les prix des aliments devraient augmenter de 8% le mois prochain.',
        'Bulk order Alfalfa hay now.' => 'Commandez du foin de luzerne en vrac maintenant.',
        'Saves ~$1,200.' => 'Économise environ 1 200 $.',
        'Lamb group Alpha slightly underweight.' => 'Groupe d\'agneaux Alpha légèrement en sous-poids.',
        'High risk of foot rot in Barn 3 due to humidity.' => 'Risque élevé de piétin dans la grange 3 en raison de l\'humidité.'
    ],
    'ar' => [
        'Schedule hoof trimming and dry bedding.' => 'جدولة تقليم الحوافر وتوفير فراش جاف.',
        'Prevents 15 potential infections.' => 'يمنع 15 إصابة محتملة.',
        'Switch to Starter Formula X2.' => 'التبديل إلى تركيبة البداية X2.',
        '+2.5kg avg weight gain in 14 days.' => '+2.5 كجم زيادة متوسطة في الوزن خلال 14 يوماً.',
        'Introduce Ram 042.' => 'إدخال الكبش 042.',
        '85% conception probability.' => 'احتمالية الحمل 85%.',
        '12 Ewes entering optimal fertility window.' => '12 نعجة تدخل نافذة الخصوبة المثالية.',
        'Financial Prediction' => 'التوقع المالي',
        'Feed prices predicted to rise by 8% next month.' => 'من المتوقع أن ترتفع أسعار الأعلاف بنسبة 8% الشهر المقبل.',
        'Bulk order Alfalfa hay now.' => 'اطلب تبن البرسيم بكميات كبيرة الآن.',
        'Saves ~$1,200.' => 'يوفر حوالي 1,200 دولار.',
        'Lamb group Alpha slightly underweight.' => 'مجموعة الحملان ألفا تعاني من نقص طفيف في الوزن.',
        'High risk of foot rot in Barn 3 due to humidity.' => 'خطر كبير للإصابة بتعفن القدم في الحظيرة 3 بسبب الرطوبة.'
    ]
];

foreach ($langs as $lang) {
    $path = __DIR__ . "/lang/{$lang}.json";
    $json = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $json = array_merge($json, $newTranslations[$lang]);
    file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

echo "Final fixes applied.\n";
