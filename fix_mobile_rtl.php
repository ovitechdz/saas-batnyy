<?php
$path = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($path);

// But wait, what if I already replaced one above?
// At line 65: I did `{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}`
// Let's replace the others:
$content = str_replace(
    'x-transition:enter-start="-translate-x-full opacity-0"',
    'x-transition:enter-start="{{ app()->getLocale() == \'ar\' ? \'translate-x-full\' : \'-translate-x-full\' }} opacity-0"',
    $content
);
$content = str_replace(
    'x-transition:leave-end="-translate-x-full opacity-0"',
    'x-transition:leave-end="{{ app()->getLocale() == \'ar\' ? \'translate-x-full\' : \'-translate-x-full\' }} opacity-0"',
    $content
);

file_put_contents($path, $content);
echo "Fixed transitions";
