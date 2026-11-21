<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiStubController;

/*
|--------------------------------------------------------------------------
| API Routes — InvenKopi OMORA v1
|--------------------------------------------------------------------------
| Prefix: /api/v1
| Dilengkapi Route Skeleton & Controller Stub respons JSON seragam.
*/

Route::prefix('v1')->group(function () {

    // Health check
    Route::get('/ping', function () {
        return response()->json([
            'success' => true,
            'data'    => ['status' => 'ok', 'app' => 'InvenKopi OMORA']
        ]);
    });

    // M01: Autentikasi & Pengguna
    Route::post('/auth/login', [ApiStubController::class, 'login']);
    Route::post('/auth/logout', [ApiStubController::class, 'logout']);
    Route::get('/auth/me', [ApiStubController::class, 'me']);

    // M02: Master Material
    Route::get('/items', [ApiStubController::class, 'getItems']);
    Route::get('/items/{sku}', [ApiStubController::class, 'getItems']);

    // M03: Master Menu & BOM
    Route::get('/menus', [ApiStubController::class, 'getMenus']);
    Route::get('/menus/{kode_menu}/bom', [ApiStubController::class, 'getMenuBom']);

    // M05: Penjualan & Usage
    Route::get('/usage', [ApiStubController::class, 'getUsage']);

    // M09: Stock Opname (PI Form)
    Route::post('/stock-opnames', [ApiStubController::class, 'submitStockOpname']);

    // M10: Stock Variance Analysis
    Route::get('/variance', [ApiStubController::class, 'getVariance']);

    // M11: Peringatan Stok Minimum
    Route::get('/inventory/alerts', [ApiStubController::class, 'getStockAlerts']);

    // M12: Dashboard Ringkasan
    Route::get('/dashboard/summary', [ApiStubController::class, 'getDashboardSummary']);
});
