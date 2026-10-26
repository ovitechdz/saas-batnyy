<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LivestockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [\App\Modules\Authentication\Controllers\Api\V1\AuthController::class, 'login']);
Route::post('/register', [\App\Modules\Authentication\Controllers\Api\V1\AuthController::class, 'register']);

Route::middleware('jwt')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('jwt')->group(function () {
    // Livestock endpoints
    Route::apiResource('livestock', LivestockController::class)->names('api.livestock');
    
    // IoT Endpoints
    Route::post('/rfid/scan', [\App\Http\Controllers\Api\V1\IotController::class, 'handleRfidScan']);
    Route::post('/iot/telemetry', [\App\Http\Controllers\Api\V1\IotController::class, 'handleTelemetry']);

    // Health Endpoints
    Route::get('/health', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getHealthRecords']);
    Route::get('/diseases', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getDiseases']);
    Route::get('/treatments', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getTreatments']);
    Route::get('/vaccinations', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getVaccinations']);
    Route::get('/medicines', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getMedicines']);
    Route::get('/veterinary', [\App\Http\Controllers\Api\V1\HealthApiController::class, 'getVeterinaryVisits']);

    // Feed Endpoints
    Route::get('/feed-inventory', [\App\Http\Controllers\Api\V1\FeedApiController::class, 'getInventory']);
    Route::get('/feed-production', [\App\Http\Controllers\Api\V1\FeedApiController::class, 'getProductionBatches']);
    Route::get('/warehouses', [\App\Http\Controllers\Api\V1\FeedApiController::class, 'getWarehouses']);
    Route::get('/suppliers', [\App\Http\Controllers\Api\V1\FeedApiController::class, 'getSuppliers']);

    // Finance Endpoints
    Route::get('/accounts', [\App\Http\Controllers\Api\V1\FinanceApiController::class, 'getAccounts']);
    Route::get('/journal', [\App\Http\Controllers\Api\V1\FinanceApiController::class, 'getJournalEntries']);
    Route::get('/budgets', [\App\Http\Controllers\Api\V1\FinanceApiController::class, 'getBudgets']);
    Route::get('/assets', [\App\Http\Controllers\Api\V1\FinanceApiController::class, 'getAssets']);

    // QR Module
    Route::post('qr/scan', [\App\Http\Controllers\Api\V1\QRController::class, 'scan'])->name('api.qr.scan');

    // Health Module
    Route::apiResource('health', \App\Http\Controllers\Api\V1\HealthController::class)->names('api.health');

    // Feed Inventory Module
    Route::apiResource('feed-inventory', \App\Http\Controllers\Api\V1\FeedInventoryController::class)->names('api.feed-inventory');

    // Vaccination Module
    Route::apiResource('vaccinations', \App\Http\Controllers\Api\V1\VaccinationController::class)->names('api.vaccinations');

    // Breeding Cycle Module
    Route::apiResource('breeding-cycles', \App\Http\Controllers\Api\V1\BreedingCycleController::class)->names('api.breeding-cycles');
});
