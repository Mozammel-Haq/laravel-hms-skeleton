<?php

use App\Http\Controllers\Api\ApoointmentsApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\DoctorsApiController;
use App\Http\Controllers\Api\PatientAuthController;
use App\Http\Controllers\Api\PatientClinicsController;
use App\Http\Controllers\Api\PatientDashboardController;
use App\Http\Controllers\Api\PatientProfileController;
use App\Http\Controllers\Api\LabResultApiController;
use App\Http\Controllers\Api\MedicalHistoryApiController;
use App\Http\Controllers\Api\PrescriptionApiController;
use App\Http\Controllers\Api\VitalsApiController;
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
        Route::get('dashboard/stats', [PatientDashboardController::class, 'stats']);
        Route::get('clinics', [PatientClinicsController::class, 'index']);

        // Get Appointments List
        Route::get('appointments', [ApoointmentsApiController::class, 'index']);
        Route::post('appointments', [ApoointmentsApiController::class, 'store']);
        Route::get('appointments/slots', [ApoointmentsApiController::class, 'slots']);

        // Appointment Requests (Cancel/Reschedule)
        Route::get('appointment-requests', [PatientAppointmentRequestController::class, 'index']);
        Route::post('appointment-requests', [PatientAppointmentRequestController::class, 'store']);

        Route::get('prescriptions', [PrescriptionApiController::class, 'index']);
        Route::get('lab-results', [LabResultApiController::class, 'index']);
        Route::get('vitals', [VitalsApiController::class, 'index']);
        Route::get('medical-history', [MedicalHistoryApiController::class, 'index']);

        // Get Doctors + Departments List
        Route::get('doctors', [BookingApiController::class, 'index']);

        Route::post('change-password', [PatientAuthController::class, 'changePassword']);
        Route::put('profile/update/{id}', [PatientProfileController::class, 'update']);
    });
});
