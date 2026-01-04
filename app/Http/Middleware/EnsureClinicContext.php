<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicContext
{

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $selectedClinicId = Session::get('selected_clinic_id');
        $clinicId = $user->hasRole('Super Admin') && $selectedClinicId ? $selectedClinicId : $user->clinic_id;

        if (!$clinicId) {
            abort(403, 'Clinic context missing');
        }

        // Set tenant context ONCE per request
        TenantContext::setClinicId($clinicId);

        return $next($request);
    }
}
