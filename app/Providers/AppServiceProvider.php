<?php

namespace App\Providers;

use App\Models\Clinic;
use App\Support\TenantContext;
use App\Models\Patient;
use App\Policies\PatientPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        View::composer('components.layout.sidebar', function ($view) {
            $user = Auth::user();
            $currentClinic = null;
            if (TenantContext::hasClinic()) {
                $currentClinic = Clinic::find(TenantContext::getClinicId());
            } elseif ($user && $user->clinic_id) {
                $currentClinic = Clinic::find($user->clinic_id);
            }

            $allClinics = collect();
            $doctorClinics = collect();

            if ($user) {
                if ($user->hasRole('Super Admin')) {
                    $allClinics = Clinic::orderBy('name')->get();
                }
                if ($user->hasRole('Doctor') && $user->doctor) {
                    $doctorClinics = $user->doctor->clinics()->orderBy('name')->get();
                }
            }

            $view->with([
                'currentClinic' => $currentClinic,
                'allClinics' => $allClinics,
                'doctorClinics' => $doctorClinics,
            ]);
        });
    }
}
