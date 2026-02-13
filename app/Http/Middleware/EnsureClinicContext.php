<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureClinicContext
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        /**
         * ✅ Super Admin = SYSTEM CONTEXT
         * No tenant should be set automatically.
         */
        if ($user->hasRole('Super Admin')) {
            if (Session::has('selected_clinic_id')) {
                TenantContext::setClinicId(Session::get('selected_clinic_id'));

                return $next($request);
            }

            TenantContext::setClinicId(null);

            return $next($request);
        }

        $selectedClinicId = Session::get('selected_clinic_id');
        $clinicId = $user->clinic_id;

        if ($selectedClinicId) {
            if (
                $user->hasRole('Doctor')
                && $user->doctor
                && $user->doctor->clinics()->whereKey($selectedClinicId)->exists()
            ) {
                $clinicId = $selectedClinicId;
            }
        }

        if (! $clinicId) {
            abort(403, 'Clinic context missing');
        }

        TenantContext::setClinicId($clinicId);

        return $next($request);
    }
}
