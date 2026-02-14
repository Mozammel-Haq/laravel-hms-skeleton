<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();
        
        // Ensure user is staff/admin and has a clinic_id
        if (!$user->clinic_id && !$user->hasRole('super-admin')) {
            Auth::guard('web')->logout();
            return response()->json([
                'message' => 'Unauthorized. No clinic assigned.'
            ], 403);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'clinic_id' => $user->clinic_id,
            'action' => 'login_api',
            'description' => 'Staff logged in via API v2',
            'entity_type' => 'App\Models\User',
            'entity_id' => $user->id,
            'ip_address' => $request->ip(),
        ]);

        $token = $user->createToken('hrm-dashboard')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user->load('roles'),
            'token' => $token,
            'message' => 'Logged in successfully'
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'clinic_id' => $user->clinic_id,
                'action' => 'logout_api',
                'description' => 'Staff logged out via API v2',
                'entity_type' => 'App\Models\User',
                'entity_id' => $user->id,
                'ip_address' => $request->ip(),
            ]);
        }

        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        } else {
            Auth::guard('web')->logout();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
