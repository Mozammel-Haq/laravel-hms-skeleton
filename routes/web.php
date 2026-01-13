<?php

use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\IpdController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\BedAssignmentController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\LabResultsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SystemController;
use App\Http\Middleware\EnsureClinicContext;
use App\Models\Clinic;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');
require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', EnsureClinicContext::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //=====Switch Clinic=======
    Route::get('/system/switch-clinic/{clinic}', [SystemController::class, 'switchClinic'])->name('system.switch-clinic');
    Route::get('/system/clear-clinic', [SystemController::class, 'clearClinicContext'])->name('system.clear-clinic');

    Route::get('/doctor/switch-clinic/{clinic}', function (Clinic $clinic) {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Doctor')) {
            abort(403);
        }
        if (!$user->doctor || !$user->doctor->clinics()->whereKey($clinic->id)->exists()) {
            abort(403);
        }
        session(['selected_clinic_id' => $clinic->id]);
        return redirect()->route('dashboard');
    })->name('doctor.switch-clinic');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patients
    Route::resource('patients', PatientController::class)->middleware('can:view_patients');

    // Appointments
    Route::resource('appointments', AppointmentController::class)->middleware('can:view_appointments');
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status.update');
    Route::get('api/doctors/{doctor}/slots', [AppointmentController::class, 'getSlots'])->name('api.doctors.slots');

    // Appointment Booking (New Interface)
    Route::prefix('booking/appointments')->name('appointments.booking.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AppointmentBookingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\AppointmentBookingController::class, 'store'])->name('store');
        Route::get('/{doctor}', [\App\Http\Controllers\AppointmentBookingController::class, 'show'])->name('show');
        Route::get('/{doctor}/slots', [\App\Http\Controllers\AppointmentBookingController::class, 'getSlots'])->name('slots');
        Route::get('/{doctor}/fee', [\App\Http\Controllers\AppointmentBookingController::class, 'getFee'])->name('fee');
    });

    // Clinical (Consultations & Prescriptions)
    Route::prefix('clinical')->name('clinical.')->group(function () {
        Route::get('consultations', [ConsultationController::class, 'index'])->name('consultations.index')->middleware('can:view_consultations');
        // Start consultation from an appointment

        Route::middleware('can:create,App\Models\Consultation')->group(function () {
            Route::get('consultations/{appointment}/create', [ConsultationController::class, 'create'])->name('consultations.create');
            Route::post('consultations/{appointment}', [ConsultationController::class, 'store'])->name('consultations.store');
        });

        Route::get('consultations/{consultation}', [ConsultationController::class, 'show'])
            ->name('consultations.show')
            ->middleware('can:view_consultations');

        // Prescriptions
        Route::get(
            'prescriptions/create/{consultation}',
            [PrescriptionController::class, 'create']
        )->name('prescriptions.create.withConsultation')
            ->middleware('can:create,App\Models\Prescription');

        Route::post(
            'prescriptions/{consultation}',
            [PrescriptionController::class, 'store']
        )->name('prescriptions.store')
            ->middleware('can:create,App\Models\Prescription');


        Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'show'])
            ->middleware('can:view_prescriptions');


        Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])
            ->name('prescriptions.print')
            ->middleware('can:view_prescriptions');
    });

    // Doctor Schedule Exceptions
    Route::prefix('doctor/schedule')->name('doctor.schedule.')->group(function () {
        Route::resource('exceptions', \App\Http\Controllers\DoctorScheduleExceptionController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    Route::prefix('billing')->name('billing.')->group(function () {

        // List all invoices
        Route::get('/', [BillingController::class, 'index'])
            ->name('index')
            ->middleware('can:view_billing');

        // Create invoice
        Route::get('/create', [BillingController::class, 'create'])
            ->name('create')
            ->middleware('can:create_invoices');

        // Store invoice
        Route::post('/store', [BillingController::class, 'store'])
            ->name('store')
            ->middleware('can:create_invoices');

        // Show single invoice
        Route::get('/{invoice}', [BillingController::class, 'show'])
            ->whereNumber('invoice')
            ->name('show')
            ->middleware('can:view_billing');

        // Payment routes
        Route::get('/{invoice}/payment', [BillingController::class, 'addPayment'])
            ->whereNumber('invoice')
            ->name('payment.add')
            ->middleware('can:process_payments');

        Route::post('/{invoice}/payment', [BillingController::class, 'storePayment'])
            ->whereNumber('invoice')
            ->name('payment.store')
            ->middleware('can:process_payments');

        // AJAX route to fetch patient pending items (consultations, lab tests, medicines)
        Route::get('/patient-items/{patient}', [BillingController::class, 'getPatientItems'])
            ->name('patient-items');
    });


    // Pharmacy & Inventory
    Route::prefix('pharmacy')->name('pharmacy.')->middleware('can:view_pharmacy')->group(function () {
        // POS (Point of Sale)
        Route::get('/', [PharmacyController::class, 'index'])->name('index');
        Route::get('/pos', [PharmacyController::class, 'create'])->name('create'); // POS Screen
        Route::post('/sale', [PharmacyController::class, 'store'])->name('store');
        Route::get('/sale/{pharmacySale}', [PharmacyController::class, 'show'])->name('show');

        // Inventory (Batches)
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/add', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

        // Medicine Catalog
        Route::get('/medicines/search', [MedicineController::class, 'search'])->name('medicines.search');
        Route::resource('medicines', MedicineController::class);
    });

    // IPD (Inpatient Department)
    Route::prefix('ipd')->name('ipd.')->middleware('can:view_ipd')->group(function () {
        Route::get('/', [IpdController::class, 'index'])->name('index');
        Route::get('/admit', [IpdController::class, 'create'])->name('create');
        Route::post('/admit', [IpdController::class, 'store'])->name('store');
        Route::get('/wards', [WardController::class, 'index'])->name('wards.index');
        Route::get('/wards/create', [WardController::class, 'create'])->name('wards.create');
        Route::post('/wards', [WardController::class, 'store'])->name('wards.store');
        Route::get('/wards/{ward}/edit', [WardController::class, 'edit'])->name('wards.edit');
        Route::put('/wards/{ward}', [WardController::class, 'update'])->name('wards.update');
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::get('/beds', [BedController::class, 'index'])->name('beds.index');
        Route::get('/beds/create', [BedController::class, 'create'])->name('beds.create');
        Route::post('/beds', [BedController::class, 'store'])->name('beds.store');
        Route::get('/beds/{bed}/edit', [BedController::class, 'edit'])->name('beds.edit');
        Route::put('/beds/{bed}', [BedController::class, 'update'])->name('beds.update');
        Route::get('/{admission}', [IpdController::class, 'show'])->whereNumber('admission')->name('show');

        // Bed Management
        Route::get('/{admission}/assign-bed', [IpdController::class, 'assignBed'])->whereNumber('admission')->name('assign-bed');
        Route::post('/{admission}/assign-bed', [IpdController::class, 'storeBedAssignment'])->whereNumber('admission')->name('store-bed');
        Route::get('/bed-assignments', [BedAssignmentController::class, 'index'])->name('bed_assignments.index');
        Route::get('/bed-assignments/{bedAssignment}', [BedAssignmentController::class, 'show'])->name('bed_assignments.show');

        // Discharge
        Route::get('/{admission}/discharge', [IpdController::class, 'discharge'])->whereNumber('admission')->name('discharge');
        Route::post('/{admission}/discharge', [IpdController::class, 'storeDischarge'])->whereNumber('admission')->name('store-discharge');
        Route::get('/rounds', [IpdController::class, 'roundsIndex'])->name('rounds.index');
        Route::get('/bed-status', [IpdController::class, 'bedStatus'])->name('bed_status');
    });

    // Laboratory
    Route::prefix('lab')->name('lab.')->middleware('can:view_lab')->group(function () {
        Route::get('/', [LabController::class, 'index'])->name('index');
        Route::get('/order', [LabController::class, 'create'])->name('create');
        Route::post('/order', [LabController::class, 'store'])->name('store');
        Route::get('/order/{order}', [LabController::class, 'show'])->name('show');
        Route::get('/catalog', [LabTestController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/create', [LabTestController::class, 'create'])->name('catalog.create');
        Route::post('/catalog', [LabTestController::class, 'store'])->name('catalog.store');

        // Results
        Route::get('/results', [LabResultsController::class, 'index'])->name('results.index');
        Route::get('/order/{order}/result', [LabController::class, 'addResult'])->name('result.add');
        Route::post('/order/{order}/result', [LabController::class, 'storeResult'])->name('result.store');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('can:view_reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/compare', [ReportController::class, 'compare'])->name('compare');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/demographics', [ReportController::class, 'patientDemographics'])->name('demographics');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/doctor-performance', [ReportController::class, 'doctorPerformance'])->name('doctor_performance');
        Route::get('/tax', [ReportController::class, 'tax'])->name('tax');
    });

    // --- Admin & Settings ---

    // System Clinics (Super Admin only via policy)
    Route::resource('clinics', ClinicController::class)
        ->middleware('can:viewAny,can:create,can:update,can:delete');

    // Doctors Management
    Route::get('/doctors/assignment', [\App\Http\Controllers\Extras\DoctorsExtrasController::class, 'assignment'])->name('doctors.assignment')->middleware('can:view_doctors');
    Route::post('/doctors/assignment/{doctor}', [\App\Http\Controllers\Extras\DoctorAssignmentController::class, 'update'])
        ->name('doctors.assignment.update')
        ->middleware('can:view_doctors');
    Route::get('/doctors/schedules/events', [\App\Http\Controllers\Extras\DoctorsExtrasController::class, 'getCalendarEvents'])->name('doctors.schedules.events')->middleware('can:view_doctors');
    Route::get('/doctors/schedules', [\App\Http\Controllers\Extras\DoctorsExtrasController::class, 'schedules'])->name('doctors.schedules')->middleware('can:view_doctors');

    // Admin Schedule Exceptions
    Route::get('/doctors/schedule/exceptions', [\App\Http\Controllers\AdminScheduleExceptionController::class, 'index'])->name('admin.schedule.exceptions.index')->middleware('can:view_doctors');
    Route::patch('/doctors/schedule/exceptions/{exception}', [\App\Http\Controllers\AdminScheduleExceptionController::class, 'update'])->name('admin.schedule.exceptions.update')->middleware('can:manage_doctor_schedule');

    Route::resource('doctors', DoctorController::class)->middleware('can:view_doctors');
    Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule'])->name('doctors.schedule')->middleware('can:manage_doctor_schedule');
    Route::put('doctors/{doctor}/schedule', [DoctorController::class, 'updateSchedule'])->name('doctors.schedule.update')->middleware('can:manage_doctor_schedule');

    // Staff Management (Users & Roles)
    Route::resource('staff', StaffController::class)->middleware('can:view_staff');

    // Roles & Permissions (Super Admin Only)
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);

        Route::put(
            'permissions/role/{role}',
            [PermissionController::class, 'updateRolePermissions']
        )->name('permissions.updateRolePermissions');

        Route::resource(
            'super-admin-users',
            AdminUsersController::class
        )->parameters(['super-admin-users' => 'user']);

        Route::resource(
            'clinic-admin-users',
            AdminUsersController::class
        )->parameters(['clinic-admin-users' => 'user']);
    });



    // Departments
    Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']); // simplified
    Route::resource('visits', VisitController::class)->only(['index', 'show', 'create', 'store']);

    Route::prefix('pharmacy')->name('pharmacy.')->middleware('can:view_pharmacy')->group(function () {
        Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    });

    // Clinic Profile
    Route::view('/clinic/profile', 'clinics.profile')->name('clinics.profile');

    // Staff extras
    Route::get('/staff/passwords', [StaffController::class, 'passwords'])->name('staff.passwords')->middleware('can:view_staff');

    // Billing payments index
    Route::prefix('billing')->name('billing.')->middleware('can:view_billing')->group(function () {
        Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    });

    // Activity logs
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');

    // Doctor schedule (current doctor)
    Route::get('/doctor/schedule', [\App\Http\Controllers\Extras\DoctorSelfScheduleController::class, 'index'])->name('doctor.schedule.index');

    // Clinical extras

    // IPD extras
    Route::prefix('ipd')->name('ipd.')->middleware('can:view_ipd')->group(function () {
        // Removed duplicate view routes that caused undefined variable errors
    });

    // Vitals
    Route::prefix('vitals')->name('vitals.')->group(function () {
        Route::get('/record', [\App\Http\Controllers\Extras\VitalsController::class, 'record'])->name('record');
        Route::get('/history', [\App\Http\Controllers\Extras\VitalsController::class, 'history'])->name('history');
    });

    // Nursing Notes
    Route::prefix('nursing')->name('nursing.')->group(function () {
        Route::get('/notes', [\App\Http\Controllers\NursingController::class, 'notesIndex'])->name('notes.index');
    });

    // Payments (Accountant)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/cash', [\App\Http\Controllers\PaymentController::class, 'cash'])->name('cash');
        Route::get('/digital', [\App\Http\Controllers\PaymentController::class, 'digital'])->name('digital');
    });
});
