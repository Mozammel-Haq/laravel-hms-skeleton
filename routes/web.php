<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\IpdController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Middleware\EnsureClinicContext;
use App\Models\Clinic;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', EnsureClinicContext::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //=====Switch Clinic=======
    Route::get('/system/switch-clinic/{clinic}', function (Clinic $clinic) {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Super Admin')) {
            abort(403);
        }
        session(['selected_clinic_id' => $clinic->id]);
        return redirect()->route('dashboard');
    })->name('system.switch-clinic');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patients
    Route::resource('patients', PatientController::class)->middleware('can:view_patients');

    // Appointments
    Route::resource('appointments', AppointmentController::class)->middleware('can:view_appointments');
    Route::get('api/doctors/{doctor}/slots', [AppointmentController::class, 'getSlots'])->name('api.doctors.slots');

    // Clinical (Consultations & Prescriptions)
    Route::prefix('clinical')->name('clinical.')->group(function () {
        // Start consultation from an appointment
        Route::middleware('can:perform_consultation')->group(function () {
            Route::get('consultations/create/{appointment}', [ConsultationController::class, 'create'])->name('consultations.create');
            Route::post('consultations/{appointment}', [ConsultationController::class, 'store'])->name('consultations.store');
        });

        Route::get('consultations/{consultation}', [ConsultationController::class, 'show'])
            ->name('consultations.show')
            ->middleware('can:view_consultations');

        // Prescriptions
        Route::resource('prescriptions', PrescriptionController::class)
            ->only(['index', 'show'])
            ->middleware('can:view_prescriptions');

        Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])
            ->name('prescriptions.print')
            ->middleware('can:view_prescriptions');
    });

    // Billing & Finance
    Route::prefix('billing')->name('billing.')->middleware('can:view_billing')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('/create', [BillingController::class, 'create'])->name('create');
        Route::post('/', [BillingController::class, 'store'])->name('store');
        Route::get('/{invoice}', [BillingController::class, 'show'])->name('show');

        // Payments
        Route::get('/{invoice}/payment', [BillingController::class, 'addPayment'])->name('payment.add');
        Route::post('/{invoice}/payment', [BillingController::class, 'storePayment'])->name('payment.store');
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
        Route::resource('medicines', MedicineController::class);
    });

    // IPD (Inpatient Department)
    Route::prefix('ipd')->name('ipd.')->middleware('can:view_ipd')->group(function () {
        Route::get('/', [IpdController::class, 'index'])->name('index');
        Route::get('/admit', [IpdController::class, 'create'])->name('create');
        Route::post('/admit', [IpdController::class, 'store'])->name('store');
        Route::get('/{admission}', [IpdController::class, 'show'])->name('show');

        // Bed Management
        Route::get('/{admission}/assign-bed', [IpdController::class, 'assignBed'])->name('assign-bed');
        Route::post('/{admission}/assign-bed', [IpdController::class, 'storeBedAssignment'])->name('store-bed');

        // Discharge
        Route::get('/{admission}/discharge', [IpdController::class, 'discharge'])->name('discharge');
        Route::post('/{admission}/discharge', [IpdController::class, 'storeDischarge'])->name('store-discharge');
    });

    // Laboratory
    Route::prefix('lab')->name('lab.')->middleware('can:view_lab')->group(function () {
        Route::get('/', [LabController::class, 'index'])->name('index');
        Route::get('/order', [LabController::class, 'create'])->name('create');
        Route::post('/order', [LabController::class, 'store'])->name('store');
        Route::get('/order/{order}', [LabController::class, 'show'])->name('show');

        // Results
        Route::get('/order/{order}/result', [LabController::class, 'addResult'])->name('result.add');
        Route::post('/order/{order}/result', [LabController::class, 'storeResult'])->name('result.store');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('can:view_reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/compare', [ReportController::class, 'compare'])->name('compare');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/demographics', [ReportController::class, 'patientDemographics'])->name('demographics');
    });

    // --- Admin & Settings ---

    // System Clinics (Super Admin only via policy)
    Route::resource('clinics', \App\Http\Controllers\ClinicController::class)
        ->middleware('can:viewAny,App\Models\Clinic');

    // Doctors Management
    Route::resource('doctors', DoctorController::class)->middleware('can:view_doctors');
    Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule'])->name('doctors.schedule')->middleware('can:manage_doctor_schedule');
    Route::put('doctors/{doctor}/schedule', [DoctorController::class, 'updateSchedule'])->name('doctors.schedule.update')->middleware('can:manage_doctor_schedule');

    // Staff Management (Users & Roles)
    Route::resource('staff', StaffController::class)->middleware('can:view_staff');

    // Departments
    Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']); // simplified
});
