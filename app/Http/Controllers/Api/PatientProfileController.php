<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * PatientProfileController
 *
 * Handles API requests for updating the patient's profile.
 * Supports updating personal information, profile photo, and password.
 */
class PatientProfileController extends Controller
{
    /**
     * Update the patient's profile.
     * Validates input data and handles file uploads for profile photos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('patients')->ignore($patient->id),
            ],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // Password validation
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Handle password change
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $patient->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['The current password is incorrect']]
                ], 422);
            }

            // Hash and update password
            $data['password'] = Hash::make($request->new_password);
            $data['password_changed_at'] = now();
        }

        // Remove password fields from data array to avoid storing them directly
        unset($data['current_password'], $data['new_password']);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('assets/img/patients');

                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }

                try {
                    // Delete old photo if exists
                    if ($patient->profile_photo && file_exists(public_path($patient->profile_photo))) {
                        unlink(public_path($patient->profile_photo));
                    }
                } catch (\Exception $e) {
                    Log::error('File delete failed: ' . $e->getMessage());
                }

                try {
                    $file->move($destination, $filename);
                    $data['profile_photo'] = 'assets/img/patients/' . $filename;
                } catch (\Exception $e) {
                    Log::error('File upload failed: ' . $e->getMessage());
                }
            }
        }

        $patient->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $patient->fresh(),
        ]);
    }
}
