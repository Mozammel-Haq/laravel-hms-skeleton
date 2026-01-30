<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Handles the display of staff credentials or password management interface.
 *
 * Responsibilities:
 * - Listing staff users for password management
 * - Filtering out Super Admin accounts
 */
class StaffPasswordsController extends Controller
{
    /**
     * Display a listing of staff users for password management.
     *
     * Filters out Super Admin users to prevent unauthorized access modification.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $staff = User::whereHas('roles', fn($q) => $q->where('name', '!=', 'Super Admin'))->orderBy('name')->get();
        return view('staff.passwords', compact('staff'));
    }
}
