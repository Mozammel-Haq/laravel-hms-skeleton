<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SystemController extends Controller
{
    /**
     * Switch the active clinic context for Super Admin.
     */
    public function switchClinic(Clinic $clinic)
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin can switch to any clinic
        } elseif ($user->hasRole('Doctor')) {
            // Doctor can only switch to assigned clinics
            if (!$user->doctor || !$user->doctor->clinics()->whereKey($clinic->id)->exists()) {
                Log::warning('Unauthorized doctor clinic switch attempt', [
                    'user_id' => $user->id,
                    'clinic_id' => $clinic->id
                ]);
                abort(403, 'You do not have access to this clinic.');
            }
        } else {
            Log::warning('Unauthorized clinic switch attempt', ['user_id' => $user->id]);
            abort(403);
        }

        try {
            Session::put('selected_clinic_id', $clinic->id);

            Log::info('User switched clinic context', [
                'user_id' => $user->id,
                'role' => $user->roles->first()?->name,
                'clinic_id' => $clinic->id,
                'clinic_name' => $clinic->name
            ]);

            return redirect()->back()->with('success', 'Switched to ' . $clinic->name);
        } catch (\Exception $e) {
            Log::error('Clinic switch failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to switch clinic.');
        }
    }

    /**
     * Clear the active clinic context (Global View).
     */
    public function clearClinicContext()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        try {
            Session::forget('selected_clinic_id');

            Log::info('Super Admin cleared clinic context (Global View)', [
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Switched to Global View');
        } catch (\Exception $e) {
            Log::error('Clinic context clear failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to reset view.');
        }
    }
}
