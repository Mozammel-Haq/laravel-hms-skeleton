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
        // Fetch users with their roles and department
        // Assuming users have a department_id or similar, but let's stick to roles first
        $staff = User::with(['roles'])
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
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }
}
