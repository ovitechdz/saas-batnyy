<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LivestockController;
use App\Http\Controllers\Web\WaterManagementController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'fr', 'ar'])) {
        session()->put('app_locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Web\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/under-construction', function () {
        return view('coming-soon');
    })->name('under-construction');

    Route::get('/livestock', [\App\Http\Controllers\Web\LivestockController::class, 'index'])->name('livestock.index');
    Route::get('/livestock/create', [\App\Http\Controllers\Web\LivestockController::class, 'create'])->name('livestock.create');
    Route::post('/livestock', [\App\Http\Controllers\Web\LivestockController::class, 'store'])->name('livestock.store');
    Route::post('/livestock/bulk', [\App\Http\Controllers\Web\LivestockController::class, 'bulkAction'])->name('livestock.bulk');
    Route::get('/livestock/{id}', [\App\Http\Controllers\Web\LivestockController::class, 'show'])->name('livestock.show');
    Route::post('/livestock/{id}/photo', [\App\Http\Controllers\Web\LivestockController::class, 'uploadPhoto'])->name('livestock.photo');
    Route::get('/livestock/{id}/edit', [\App\Http\Controllers\Web\LivestockController::class, 'edit'])->name('livestock.edit');
    Route::put('/livestock/{id}', [\App\Http\Controllers\Web\LivestockController::class, 'update'])->name('livestock.update');
    Route::delete('/livestock/{id}', [\App\Http\Controllers\Web\LivestockController::class, 'destroy'])->name('livestock.destroy');

    Route::get('/inventory', [\App\Http\Controllers\Web\InventoryController::class, 'index'])->name('inventory.index');

    // Water Management
    Route::get('/water', [WaterManagementController::class, 'index'])->name('water.index');
    Route::get('/water/create', [WaterManagementController::class, 'create'])->name('water.create');
    Route::post('/water', [WaterManagementController::class, 'store'])->name('water.store');
    Route::get('/water/{id}', [WaterManagementController::class, 'show'])->name('water.show');
    Route::get('/water/{id}/edit', [WaterManagementController::class, 'edit'])->name('water.edit');
    Route::put('/water/{id}', [WaterManagementController::class, 'update'])->name('water.update');
    Route::delete('/water/{id}', [WaterManagementController::class, 'destroy'])->name('water.destroy');
    Route::post('/water/{id}/readings', [WaterManagementController::class, 'storeReading'])->name('water.readings.store');
    Route::post('/inventory', [\App\Http\Controllers\Web\InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}', [\App\Http\Controllers\Web\InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [\App\Http\Controllers\Web\InventoryController::class, 'destroy'])->name('inventory.destroy');

    Route::post('/health-records', [\App\Http\Controllers\Web\HealthRecordController::class, 'store'])->name('health-records.store');
    Route::delete('/health-records/{id}', [\App\Http\Controllers\Web\HealthRecordController::class, 'destroy'])->name('health-records.destroy');

    Route::post('/vaccinations', [\App\Http\Controllers\Web\VaccinationController::class, 'store'])->name('vaccinations.store');
    Route::delete('/vaccinations/{id}', [\App\Http\Controllers\Web\VaccinationController::class, 'destroy'])->name('vaccinations.destroy');

    Route::get('/breeding-cycles', [\App\Http\Controllers\Web\BreedingCycleController::class, 'index'])->name('breeding-cycles.index');
    Route::post('/breeding-cycles', [\App\Http\Controllers\Web\BreedingCycleController::class, 'store'])->name('breeding-cycles.store');
    Route::put('/breeding-cycles/{id}', [\App\Http\Controllers\Web\BreedingCycleController::class, 'update'])->name('breeding-cycles.update');

    // Fallback route for serving storage files on Windows XAMPP environments
    // where symlinks (junctions) might be blocked by Apache configuration
    Route::get('/media/{path}', function ($path) {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            abort(404);
        }
        
        $mime = mime_content_type($fullPath);
        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400'
        ]);
    })->where('path', '.*')->name('media.file');

    Route::get('/reports', [\App\Http\Controllers\Web\ReportsController::class, 'index'])->name('reports.index');

    Route::get('/settings', [\App\Http\Controllers\Web\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Web\SettingsController::class, 'update'])->name('settings.update');

    Route::get('/staff', [\App\Http\Controllers\Web\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [\App\Http\Controllers\Web\StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{id}', [\App\Http\Controllers\Web\StaffController::class, 'destroy'])->name('staff.destroy');

    // Financial routes
    Route::get('/financial', [\App\Http\Controllers\Web\FinancialController::class, 'index'])->name('financial.index');
    Route::post('/financial', [\App\Http\Controllers\Web\FinancialController::class, 'store'])->name('financial.store');
    Route::delete('/financial/{id}', [\App\Http\Controllers\Web\FinancialController::class, 'destroy'])->name('financial.destroy');

    // Tasks routes
    Route::get('/tasks', [\App\Http\Controllers\Web\TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [\App\Http\Controllers\Web\TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [\App\Http\Controllers\Web\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [\App\Http\Controllers\Web\TaskController::class, 'destroy'])->name('tasks.destroy');

    // IoT Dashboard routes
    Route::get('/iot/dashboard', [\App\Http\Controllers\Web\IotDashboardController::class, 'index'])->name('iot.dashboard');

    // Health Dashboard
    Route::get('/health/dashboard', [\App\Http\Controllers\Web\HealthDashboardController::class, 'index'])->name('health.dashboard');
    Route::get('/health/animal/{id}', [\App\Http\Controllers\Web\HealthProfileController::class, 'show'])->name('health.profile');

    // Feed Management
    Route::get('/feed/dashboard', [\App\Http\Controllers\Web\FeedDashboardController::class, 'index'])->name('feed.dashboard');
    Route::post('/feed/apply-ai-plan', [\App\Http\Controllers\Web\FeedDashboardController::class, 'applyAiPlan'])->name('feed.apply_ai_plan');
    Route::get('/feed/apply-ai-plan', function () { return redirect()->route('feed.dashboard'); });
    Route::get('/feed/production', [\App\Http\Controllers\Web\FeedProductionController::class, 'index'])->name('feed.production');
    Route::post('/feed/production/batch', [\App\Http\Controllers\Web\FeedProductionController::class, 'store'])->name('feed.production.store');
    Route::post('/feed/production/silage', [\App\Http\Controllers\Web\FeedProductionController::class, 'storeSilage'])->name('feed.production.silage.store');

    // Farm Operations
    Route::prefix('farm')->name('farm.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Web\FarmOperationsController::class, 'index'])->name('dashboard');
        Route::resource('zones', \App\Http\Controllers\Web\ZoneController::class);
        Route::resource('barns', \App\Http\Controllers\Web\BarnController::class);
        Route::resource('equipment', \App\Http\Controllers\Web\EquipmentController::class);
    });

    // Finance Management
    Route::get('/finance/dashboard', [\App\Http\Controllers\Web\FinanceDashboardController::class, 'index'])->name('finance.dashboard');
    Route::get('/finance/ledger', [\App\Http\Controllers\Web\LedgerController::class, 'index'])->name('finance.ledger');
    Route::post('/finance/ledger', [\App\Http\Controllers\Web\LedgerController::class, 'store'])->name('finance.ledger.store');

    // Energy Management
    Route::get('/energy', [\App\Http\Controllers\Web\EnergyManagementController::class, 'index'])->name('energy.index');
    Route::post('/energy/consumption', [\App\Http\Controllers\Web\EnergyManagementController::class, 'storeConsumption'])->name('energy.consumption.store');


    // Transportation
    Route::get('/transportation', [\App\Http\Controllers\Web\TransportationController::class, 'index'])->name('transportation.index');
    Route::post('/transportation', [\App\Http\Controllers\Web\TransportationController::class, 'store'])->name('transportation.store');
    Route::patch('/transportation/{id}/status', [\App\Http\Controllers\Web\TransportationController::class, 'updateStatus'])->name('transportation.status');
    Route::post('/transportation/vehicles', [\App\Http\Controllers\Web\TransportationController::class, 'storeVehicle'])->name('transportation.vehicles.store');

    // Customers
    Route::get('/customers', [\App\Http\Controllers\Web\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [\App\Http\Controllers\Web\CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{id}', [\App\Http\Controllers\Web\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [\App\Http\Controllers\Web\CustomerController::class, 'destroy'])->name('customers.destroy');

    // Marketplace
    Route::get('/marketplace', [\App\Http\Controllers\Web\MarketplaceController::class, 'index'])->name('marketplace.index');

    // Admin Dashboard
    Route::get('/admin', [\App\Http\Controllers\Web\AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // AI Hub
    Route::get('/ai', [\App\Http\Controllers\Web\AiHubController::class, 'index'])->name('ai.dashboard');
});
