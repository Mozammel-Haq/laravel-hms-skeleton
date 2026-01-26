<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenClinicContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && method_exists($user, 'getAttribute')) {
            $clinicId = $user->getAttribute('clinic_id');
            if ($clinicId) {
                TenantContext::setClinicId($clinicId);
            }
        }

        return $next($request);
    }
}
