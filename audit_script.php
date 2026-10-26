<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Route;

$report = [
    'missing_files' => [],
    'broken_routes' => [],
    'empty_files' => [],
    'missing_controllers' => [],
    'missing_models' => [],
    'syntax_errors' => [],
];

// Check routes
$routes = Route::getRoutes();
foreach ($routes as $route) {
    $action = $route->getAction();
    if (isset($action['controller'])) {
        $controllerStr = $action['controller'];
        if (strpos($controllerStr, '@') !== false) {
            list($controller, $method) = explode('@', $controllerStr);
            if (!class_exists($controller)) {
                $report['missing_controllers'][] = "Controller missing for route: " . $route->uri() . " ($controller)";
                $report['broken_routes'][] = $route->uri();
            } elseif (!method_exists($controller, $method)) {
                $report['broken_routes'][] = "Method $method missing in $controller for route " . $route->uri();
            }
        }
    }
}

// Scan all files in app
$di = new RecursiveDirectoryIterator(__DIR__ . '/app');
$it = new RecursiveIteratorIterator($di);
foreach ($it as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (trim($content) === '' || trim($content) === '<?php') {
            $report['empty_files'][] = $file->getPathname();
        }
        
        // Very basic syntax check
        // exec('php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $output, $return_var);
        // if ($return_var !== 0) {
        //     $report['syntax_errors'][] = $file->getPathname() . ': ' . implode("\n", $output);
        // }
    }
}

// Check views
$di = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$it = new RecursiveIteratorIterator($di);
foreach ($it as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        if (trim($content) === '') {
            $report['empty_files'][] = $file->getPathname();
        }
    }
}

file_put_contents('audit_report_data.json', json_encode($report, JSON_PRETTY_PRINT));
echo "Done\n";
