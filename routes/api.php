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
use App\Http\Controllers\Api\V2\DepartmentController as ApiDepartmentController;
use App\Http\Controllers\Api\V2\DesignationController as ApiDesignationController;
use App\Http\Controllers\Api\V2\InquiryController;
use App\Http\Controllers\Api\V2\ProcurementController;
use App\Http\Controllers\Api\V2\LeaveRequestController;
use App\Http\Controllers\Api\V2\HrmShiftAssignmentController;
use App\Http\Controllers\Api\V2\HrmShiftController;
use App\Http\Controllers\Api\V2\HrmHolidayController;
use App\Http\Controllers\Api\V2\HrmAttendanceController;
use App\Http\Controllers\Api\V2\HrmTimesheetController;
use App\Http\Controllers\Api\V2\HrmOvertimeController;
use App\Http\Controllers\Api\V2\HrmLeaveBalanceController;
use App\Http\Controllers\Api\V2\HrmLeaveTypeController;
use App\Http\Controllers\Api\V2\HrmPayrollAllowanceController;
use App\Http\Controllers\Api\V2\HrmPayrollDeductionController;
use App\Http\Controllers\Api\V2\HrmPayrollRunController;
use App\Http\Controllers\Api\V2\HrmPayrollTaxController;
use App\Http\Controllers\Api\V2\HrmPayslipController;
use App\Http\Controllers\Api\V2\HrmTrainingCourseController;
use App\Http\Controllers\Api\V2\HrmTrainingSessionController;
use App\Http\Controllers\Api\V2\HrmTrainingEvaluationController;
use App\Http\Controllers\Api\V2\HrmJobPostController;
use App\Http\Controllers\Api\V2\HrmCandidateController;
use App\Http\Controllers\Api\V2\HrmInterviewController;
use App\Http\Controllers\Api\V2\HrmJobOfferController;
use App\Http\Controllers\Api\V2\HrmOnboardingController;
use App\Http\Controllers\Api\V2\HrmSalaryStructureController;
use App\Http\Controllers\Api\V2\StaffController;
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
        Route::get('shifts', [HrmShiftController::class, 'index']);
        Route::post('shifts', [HrmShiftController::class, 'store']);
        Route::put('shifts/{shift}', [HrmShiftController::class, 'update']);
        Route::delete('shifts/{shift}', [HrmShiftController::class, 'destroy']);
        Route::get('shift-assignments', [HrmShiftAssignmentController::class, 'index']);
        Route::post('shift-assignments', [HrmShiftAssignmentController::class, 'store']);
        Route::put('shift-assignments/{assignment}', [HrmShiftAssignmentController::class, 'update']);
        Route::delete('shift-assignments/{assignment}', [HrmShiftAssignmentController::class, 'destroy']);
        Route::get('leaves', [LeaveRequestController::class, 'index']);
        Route::post('leaves', [LeaveRequestController::class, 'store']);
        Route::get('leaves/{leave}', [LeaveRequestController::class, 'show']);
        Route::patch('leaves/{leave}', [LeaveRequestController::class, 'update']);
        Route::get('leave-types', [HrmLeaveTypeController::class, 'index']);
        Route::post('leave-types', [HrmLeaveTypeController::class, 'store']);
        Route::put('leave-types/{leaveType}', [HrmLeaveTypeController::class, 'update']);
        Route::delete('leave-types/{leaveType}', [HrmLeaveTypeController::class, 'destroy']);
        Route::get('leave-balances', [HrmLeaveBalanceController::class, 'index']);
        Route::post('leave-balances', [HrmLeaveBalanceController::class, 'store']);
        Route::put('leave-balances/{leaveBalance}', [HrmLeaveBalanceController::class, 'update']);
        Route::delete('leave-balances/{leaveBalance}', [HrmLeaveBalanceController::class, 'destroy']);
        Route::get('holidays', [HrmHolidayController::class, 'index']);
        Route::post('holidays', [HrmHolidayController::class, 'store']);
        Route::put('holidays/{holiday}', [HrmHolidayController::class, 'update']);
        Route::delete('holidays/{holiday}', [HrmHolidayController::class, 'destroy']);

        Route::get('attendance', [HrmAttendanceController::class, 'index']);
        Route::post('attendance', [HrmAttendanceController::class, 'store']);
        Route::put('attendance/{attendance}', [HrmAttendanceController::class, 'update']);
        Route::delete('attendance/{attendance}', [HrmAttendanceController::class, 'destroy']);

        Route::get('timesheets', [HrmTimesheetController::class, 'index']);
        Route::post('timesheets', [HrmTimesheetController::class, 'store']);
        Route::put('timesheets/{timesheet}', [HrmTimesheetController::class, 'update']);
        Route::delete('timesheets/{timesheet}', [HrmTimesheetController::class, 'destroy']);

        Route::get('overtimes', [HrmOvertimeController::class, 'index']);
        Route::post('overtimes', [HrmOvertimeController::class, 'store']);
        Route::put('overtimes/{overtime}', [HrmOvertimeController::class, 'update']);
        Route::delete('overtimes/{overtime}', [HrmOvertimeController::class, 'destroy']);

        Route::get('payroll-allowances', [HrmPayrollAllowanceController::class, 'index']);
        Route::post('payroll-allowances', [HrmPayrollAllowanceController::class, 'store']);
        Route::put('payroll-allowances/{allowance}', [HrmPayrollAllowanceController::class, 'update']);
        Route::delete('payroll-allowances/{allowance}', [HrmPayrollAllowanceController::class, 'destroy']);

        Route::get('payroll-deductions', [HrmPayrollDeductionController::class, 'index']);
        Route::post('payroll-deductions', [HrmPayrollDeductionController::class, 'store']);
        Route::put('payroll-deductions/{deduction}', [HrmPayrollDeductionController::class, 'update']);
        Route::delete('payroll-deductions/{deduction}', [HrmPayrollDeductionController::class, 'destroy']);

        Route::get('payroll-taxes', [HrmPayrollTaxController::class, 'index']);
        Route::post('payroll-taxes', [HrmPayrollTaxController::class, 'store']);
        Route::put('payroll-taxes/{tax}', [HrmPayrollTaxController::class, 'update']);
        Route::delete('payroll-taxes/{tax}', [HrmPayrollTaxController::class, 'destroy']);

        Route::get('salary-structures', [HrmSalaryStructureController::class, 'index']);
        Route::post('salary-structures', [HrmSalaryStructureController::class, 'store']);
        Route::put('salary-structures/{structure}', [HrmSalaryStructureController::class, 'update']);
        Route::delete('salary-structures/{structure}', [HrmSalaryStructureController::class, 'destroy']);

        Route::get('payroll-runs', [HrmPayrollRunController::class, 'index']);
        Route::post('payroll-runs', [HrmPayrollRunController::class, 'store']);
        Route::put('payroll-runs/{run}', [HrmPayrollRunController::class, 'update']);
        Route::delete('payroll-runs/{run}', [HrmPayrollRunController::class, 'destroy']);

        Route::get('payslips', [HrmPayslipController::class, 'index']);
        Route::post('payslips', [HrmPayslipController::class, 'store']);
        Route::put('payslips/{payslip}', [HrmPayslipController::class, 'update']);
        Route::delete('payslips/{payslip}', [HrmPayslipController::class, 'destroy']);

        Route::get('training-courses', [HrmTrainingCourseController::class, 'index']);
        Route::post('training-courses', [HrmTrainingCourseController::class, 'store']);
        Route::put('training-courses/{trainingCourse}', [HrmTrainingCourseController::class, 'update']);
        Route::delete('training-courses/{trainingCourse}', [HrmTrainingCourseController::class, 'destroy']);

        Route::get('training-sessions', [HrmTrainingSessionController::class, 'index']);
        Route::post('training-sessions', [HrmTrainingSessionController::class, 'store']);
        Route::put('training-sessions/{trainingSession}', [HrmTrainingSessionController::class, 'update']);
        Route::delete('training-sessions/{trainingSession}', [HrmTrainingSessionController::class, 'destroy']);

        Route::get('training-evaluations', [HrmTrainingEvaluationController::class, 'index']);
        Route::post('training-evaluations', [HrmTrainingEvaluationController::class, 'store']);
        Route::delete('training-evaluations/{trainingEvaluation}', [HrmTrainingEvaluationController::class, 'destroy']);

        Route::get('job-posts', [HrmJobPostController::class, 'index']);
        Route::post('job-posts', [HrmJobPostController::class, 'store']);
        Route::put('job-posts/{jobPost}', [HrmJobPostController::class, 'update']);
        Route::delete('job-posts/{jobPost}', [HrmJobPostController::class, 'destroy']);

        Route::get('candidates', [HrmCandidateController::class, 'index']);
        Route::post('candidates', [HrmCandidateController::class, 'store']);
        Route::put('candidates/{candidate}', [HrmCandidateController::class, 'update']);
        Route::delete('candidates/{candidate}', [HrmCandidateController::class, 'destroy']);

        Route::get('interviews', [HrmInterviewController::class, 'index']);
        Route::post('interviews', [HrmInterviewController::class, 'store']);
        Route::put('interviews/{interview}', [HrmInterviewController::class, 'update']);
        Route::delete('interviews/{interview}', [HrmInterviewController::class, 'destroy']);

        Route::get('job-offers', [HrmJobOfferController::class, 'index']);
        Route::post('job-offers', [HrmJobOfferController::class, 'store']);
        Route::put('job-offers/{offer}', [HrmJobOfferController::class, 'update']);
        Route::delete('job-offers/{offer}', [HrmJobOfferController::class, 'destroy']);

        Route::get('onboardings', [HrmOnboardingController::class, 'index']);
        Route::post('onboardings', [HrmOnboardingController::class, 'store']);
        Route::put('onboardings/{onboarding}', [HrmOnboardingController::class, 'update']);
        Route::delete('onboardings/{onboarding}', [HrmOnboardingController::class, 'destroy']);

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
