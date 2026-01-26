<?php

use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\DoctorsApiController;
use App\Http\Controllers\Api\PatientAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('public/doctors', [DoctorsApiController::class, 'index']);
Route::get('public/doctors/{doctor}', [DoctorsApiController::class, 'show']);

Route::get('public/clinics', [ClinicApiController::class, 'index']);
Route::get('public/clinics/{clinic}', [ClinicApiController::class, 'show']);

Route::prefix('patient')->group(function () {
    Route::post('login', [PatientAuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'api.tenant'])->group(function () {
        Route::post('logout', [PatientAuthController::class, 'logout']);
        Route::get('me', [PatientAuthController::class, 'me']);
        Route::post('change-password', [PatientAuthController::class, 'changePassword']);
    });
});
