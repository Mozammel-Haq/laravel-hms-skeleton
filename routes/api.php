<?php

use App\Http\Controllers\Api\AppointmentsApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\DoctorsApiController;
use App\Http\Controllers\Api\LabResultApiController;
use App\Http\Controllers\Api\MedicalHistoryApiController;
use App\Http\Controllers\Api\PatientAppointmentRequestController;
use App\Http\Controllers\Api\PatientAuthController;
use App\Http\Controllers\Api\PatientClinicsController;
use App\Http\Controllers\Api\PatientDashboardController;
use App\Http\Controllers\Api\PatientNotificationController;
use App\Http\Controllers\Api\PatientProfileController;
use App\Http\Controllers\Api\PrescriptionApiController;
use App\Http\Controllers\Api\VitalsApiController;
use App\Http\Controllers\Api\V2\StaffController;
use App\Http\Controllers\Api\V2\DepartmentController as ApiDepartmentController;
use App\Http\Controllers\Api\V2\DesignationController as ApiDesignationController;
use App\Http\Controllers\Api\V2\InquiryController;
use App\Http\Controllers\Api\V2\ProcurementController;
use App\Http\Controllers\Api\V2\LeaveRequestController;
use App\Http\Controllers\Api\V2\StaffAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Payment Callbacks (SSLCommerz) - Defined in API to avoid Session/CSRF issues
Route::post('/sslcommerz/success', [\App\Http\Controllers\OnlinePaymentController::class, 'sslCommerzSuccess'])->name('online-payment.sslcommerz.success');
Route::post('/sslcommerz/fail', [\App\Http\Controllers\OnlinePaymentController::class, 'sslCommerzFail'])->name('online-payment.sslcommerz.fail');
Route::post('/sslcommerz/cancel', [\App\Http\Controllers\OnlinePaymentController::class, 'sslCommerzCancel'])->name('online-payment.sslcommerz.cancel');
Route::post('/sslcommerz/ipn', [\App\Http\Controllers\OnlinePaymentController::class, 'sslCommerzIpn'])->name('online-payment.sslcommerz.ipn');

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
        Route::get('appointments', [AppointmentsApiController::class, 'index']);
        Route::get('appointments/slots', [AppointmentsApiController::class, 'slots']);
        Route::get('appointments/{id}', [AppointmentsApiController::class, 'show']);
        Route::post('appointments', [AppointmentsApiController::class, 'store']);

        // Appointment Requests (Cancel/Reschedule)
        Route::get('appointment-requests', [PatientAppointmentRequestController::class, 'index']);
        Route::post('appointment-requests', [PatientAppointmentRequestController::class, 'store']);

        Route::get('prescriptions', [PrescriptionApiController::class, 'index']);
        Route::get('lab-results', [LabResultApiController::class, 'index']);
        Route::get('vitals', [VitalsApiController::class, 'index']);
        Route::get('medical-history', [MedicalHistoryApiController::class, 'index']);

        // Billing
        Route::get('invoices', [\App\Http\Controllers\Api\PatientBillingController::class, 'index']);
        Route::get('invoices/{id}', [\App\Http\Controllers\Api\PatientBillingController::class, 'show']);
        Route::post('invoices/{id}/pay', [\App\Http\Controllers\Api\PatientBillingController::class, 'pay']);

        // IPD
        Route::get('ipd/current-admission', [\App\Http\Controllers\Api\PatientIpdController::class, 'currentAdmission']);
        Route::get('ipd/rounds', [\App\Http\Controllers\Api\PatientIpdController::class, 'rounds']);
        Route::get('ipd/billing', [\App\Http\Controllers\Api\PatientIpdController::class, 'billing']);
        Route::post('ipd/deposit', [\App\Http\Controllers\Api\PatientIpdController::class, 'payDeposit']);

        // Notifications
        Route::get('notifications', [PatientNotificationController::class, 'index']);
        Route::post('notifications/{id}/read', [PatientNotificationController::class, 'markAsRead']);
        Route::delete('notifications/{id}', [PatientNotificationController::class, 'destroy']);

        // Get Doctors + Departments List
        Route::get('doctors', [BookingApiController::class, 'index']);

        Route::post('change-password', [PatientAuthController::class, 'changePassword']);
        Route::put('profile/update/{id}', [PatientProfileController::class, 'update']);
    });
});

// Dashboard API v2 (Non-destructive)
Route::prefix('v2')->group(function () {
    // Auth
    Route::post('login', [StaffAuthController::class, 'login']);
    Route::post('logout', [StaffAuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware(['auth:sanctum', 'api.tenant'])->group(function () {
        Route::get('me', [StaffController::class, 'me']);

        // HRM
        Route::get('staff', [StaffController::class, 'index']);
        Route::get('staff/{staff}', [StaffController::class, 'show']);
        Route::post('staff', [StaffController::class, 'store']);
        Route::put('staff/{staff}', [StaffController::class, 'update']);
        Route::delete('staff/{staff}', [StaffController::class, 'destroy']);
        Route::apiResource('departments', ApiDepartmentController::class)->only(['index','store','update','destroy']);
        Route::apiResource('designations', ApiDesignationController::class)->only(['index','store','update','destroy']);
        Route::get('leaves', [LeaveRequestController::class, 'index']);
        Route::post('leaves', [LeaveRequestController::class, 'store']);
        Route::get('leaves/{leave}', [LeaveRequestController::class, 'show']);
        Route::patch('leaves/{leave}', [LeaveRequestController::class, 'update']);

        // CRM
        Route::get('inquiries', [InquiryController::class, 'index']);
        Route::post('inquiries', [InquiryController::class, 'store']);
        Route::patch('inquiries/{inquiry}', [InquiryController::class, 'update']);

        // Asset Management
        Route::get('inventory', [ProcurementController::class, 'inventory']);
        Route::get('procurements', [ProcurementController::class, 'index']);
        Route::post('procurements', [ProcurementController::class, 'store']);
        Route::post('procurements/{order}/receive', [ProcurementController::class, 'receive']);
    });
});
