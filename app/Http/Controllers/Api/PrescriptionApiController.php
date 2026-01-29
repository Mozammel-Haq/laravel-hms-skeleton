<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use App\Support\TenantContext;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrescriptionApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $user;

        // Dynamic patient resolution logic
        if ($requestedClinicId && $requestedClinicId != $user->clinic_id) {
            $foundPatient = Patient::withoutTenant()
                ->where('clinic_id', $requestedClinicId)
                ->where(function ($q) use ($user) {
                    if ($user->email) $q->where('email', $user->email);
                    if ($user->phone) $q->orWhere('phone', $user->phone);
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
            }
        }

        $clinicId = $targetPatient->clinic_id;
        TenantContext::setClinicId($clinicId);

        // Fetch prescriptions via consultation -> patient
        $prescriptions = Prescription::whereHas('consultation', function ($q) use ($targetPatient) {
            $q->where('patient_id', $targetPatient->id);
        })
            ->with(['items.medicine', 'consultation.doctor.user'])
            ->orderBy('issued_at', 'desc') // Corrected column from created_at to issued_at
            ->get();

        $medications = [];

        foreach ($prescriptions as $prescription) {
            $prescribedBy = $prescription->consultation && $prescription->consultation->doctor && $prescription->consultation->doctor->user
                ? $prescription->consultation->doctor->user->name
                : 'Unknown Doctor';

            // Use issued_at, fallback to created_at
            $startDate = $prescription->issued_at
                ? Carbon::parse($prescription->issued_at)
                : ($prescription->created_at ? Carbon::parse($prescription->created_at) : Carbon::now());

            foreach ($prescription->items as $item) {
                $duration = (int) ($item->duration_days ?? 0);
                $endDate = $startDate->copy()->addDays($duration);
                $isActive = $endDate->isFuture() || $endDate->isToday();

                $medications[] = [
                    'id' => $item->id,
                    'name' => $item->medicine ? ($item->medicine->name ?? 'Unknown Medicine') : 'Unknown Medicine',
                    'dosage' => $item->dosage ?? '',
                    'frequency' => $item->frequency ?? '',
                    'instructions' => $item->instructions ?? '',
                    'startDate' => $startDate->format('Y-m-d'),
                    'prescribedBy' => $prescribedBy,
                    'status' => $isActive ? 'Active' : 'Completed',
                    'refillsRemaining' => 0, // Placeholder
                ];
            }
        }

        return response()->json([
            'prescriptions' => $medications
        ]);
    }
}
