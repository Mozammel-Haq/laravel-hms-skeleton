<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientAuthController extends Controller
{
    public function login(Request $request)
    {
        // return response()->json($request->all());
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'clinic_code' => ['nullable', 'string'],
        ]);

        $query = Patient::withoutTenant()->where('email', $validated['email']);

        if (!empty($validated['clinic_code'])) {
            $clinic = Clinic::where('code', $validated['clinic_code'])->first();
            if (!$clinic) {
                throw ValidationException::withMessages([
                    'clinic_code' => ['Invalid clinic code'],
                ]);
            }
            $query->where('patients.clinic_id', $clinic->id);
        }

        // Get all matching patients (could be multiple if same email across clinics and no clinic_code provided)
        $patients = $query->get();

        // Filter by password
        $validPatients = [];
        foreach ($patients as $p) {
            if ($p->password && Hash::check($validated['password'], $p->password)) {
                $validPatients[] = $p;
            }
        }

        if (count($validPatients) > 1) {
            // More than one valid account found
            // We can help the user by listing the clinics where they have accounts
            $clinicInfo = [];
            foreach ($validPatients as $p) {
                if ($p->clinic) {
                    $clinicInfo[] = "{$p->clinic->name} (Code: {$p->clinic->code})";
                }
            }

            $msg = 'Multiple accounts found in: ' . implode(', ', $clinicInfo) . '. Please provide the clinic code.';

            throw ValidationException::withMessages([
                'clinic_code' => [$msg],
            ]);
        }

        if (count($validPatients) === 0) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $validPatient = $validPatients[0];

        $validPatient->forceFill([
            'last_login_at' => now(),
        ])->save();

        $token = $validPatient->createToken('patient-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $validPatient,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
        ]);

        /** @var Patient $patient */
        $patient = $request->user();

        if (!$patient || !$patient->password || !Hash::check($validated['current_password'], $patient->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $patient->forceFill([
            'password' => $validated['new_password'],
            'must_change_password' => false,
            'password_changed_at' => now(),
        ])->save();

        return response()->json(['message' => 'Password updated']);
    }
}
