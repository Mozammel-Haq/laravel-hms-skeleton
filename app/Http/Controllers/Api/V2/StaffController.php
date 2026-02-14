<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff members for the current tenant.
     */
    public function index()
    {
        $staff = User::with(['roles', 'department', 'designation'])
            ->whereNotNull('clinic_id')
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    /**
     * Get the authenticated user's details.
     */
    public function me()
    {
        $user = Auth::user();
        $user->load(['roles.permissions', 'clinic']);

        $abilities = $user->roles
            ->flatMap(fn ($role) => $role->permissions->pluck('name'))
            ->unique()
            ->values()
            ->toArray();

        $payload = array_merge($user->toArray(), ['abilities' => $abilities]);

        return response()->json([
            'status' => 'success',
            'data' => $payload
        ]);
    }
}
