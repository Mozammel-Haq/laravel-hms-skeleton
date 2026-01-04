<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Admission;
use App\Models\Appointment;
use App\Models\BedAssignment;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PharmacySale;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User as SubjectUser;
use App\Models\MedicineBatch;
use App\Models\Visit;
use App\Policies\ActivityLogPolicy;
use App\Policies\AdmissionPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\BedAssignmentPolicy;
use App\Policies\ConsultationPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\LabTestOrderPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PharmacySalePolicy;
use App\Policies\PrescriptionPolicy;
use App\Policies\MedicinePolicy;
use App\Policies\VisitPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\UserPolicy;
use App\Policies\MedicineBatchPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings.
     */
    protected $policies = [
        Patient::class => PatientPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Visit::class => VisitPolicy::class,
        Consultation::class => ConsultationPolicy::class,
        Prescription::class => PrescriptionPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Admission::class => AdmissionPolicy::class,
        BedAssignment::class => BedAssignmentPolicy::class,
        PharmacySale::class => PharmacySalePolicy::class,
        LabTestOrder::class => LabTestOrderPolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
        Department::class => DepartmentPolicy::class,
        Doctor::class => DoctorPolicy::class,
        SubjectUser::class => UserPolicy::class,
        Medicine::class => MedicinePolicy::class,
        MedicineBatch::class => MedicineBatchPolicy::class,
    ];


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Grant all permissions to Super Admin or check permissions dynamically
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
            if ($user->hasPermission($ability)) {
                return true;
            }
        });
    }
}
