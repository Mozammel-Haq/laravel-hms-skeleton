<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API V2 Routes - Enterprise Dashboards (HRM, CRM, Asset)
|--------------------------------------------------------------------------
|
| These routes are dedicated to the new Vue.js dashboards.
| They implement strict tenant isolation and role-based access.
|
*/

Route::prefix('v2')->group(function () {
    
    // Health Check & Version Info
    Route::get('/status', function () {
        return response()->json([
            'status' => 'online',
            'version' => '2.0.0',
            'environment' => config('app.env'),
            'timestamp' => now()->toIso8601String()
        ]);
    });

    // Protected Routes (Require Authentication and Clinic Context)
    Route::middleware(['auth:sanctum', 'api.tenant'])->group(function () {
        
        // HRM Routes
        Route::prefix('hrm')->group(function () {
            // Placeholder for Pilot Study: Staff Directory
            Route::get('/staff', function (Request $request) {
                return response()->json(['message' => 'HRM Staff Directory API']);
            });
        });

        // CRM Routes
        Route::prefix('crm')->group(function () {
            // Placeholder for Pilot Study: Inquiry Log
            Route::get('/inquiries', function (Request $request) {
                return response()->json(['message' => 'CRM Inquiry Log API']);
            });
        });

        // Asset Management Routes
        Route::prefix('asset')->group(function () {
            // Placeholder for Pilot Study: Inventory Alerts
            Route::get('/inventory-alerts', function (Request $request) {
                return response()->json(['message' => 'Asset Inventory Alerts API']);
            });
        });

    });
});
