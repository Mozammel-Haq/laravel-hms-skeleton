<?php

use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\DoctorsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('public/doctors', [DoctorsApiController::class, 'index']);
Route::get('public/clinics', [ClinicApiController::class, 'index']);
Route::get('public/clinics/{clinic}', [ClinicApiController::class, 'show']);
