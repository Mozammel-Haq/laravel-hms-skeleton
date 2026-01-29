<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientVital;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class VitalsApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');

        $targetPatient = $user;

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

        $vitalsHistory = PatientVital::where('patient_id', $targetPatient->id)
            ->orderBy('recorded_at', 'desc')
            ->limit(20)
            ->get();

        $latest = $vitalsHistory->first();
        $previous = $vitalsHistory->skip(1)->first();

        // Helper to calculate trend
        $calculateTrend = function ($current, $prev) {
            if (!$current || !$prev) return ['trend' => 'stable', 'change' => '0%'];

            // Simple numeric check. If non-numeric, return stable.
            // Blood pressure is "120/80", needs parsing.
            if (strpos($current, '/') !== false) {
                // Skip complex BP trend for now
                return ['trend' => 'stable', 'change' => '0%'];
            }

            $c = floatval($current);
            $p = floatval($prev);

            if ($p == 0) return ['trend' => 'stable', 'change' => '0%'];

            $diff = $c - $p;
            $pct = ($diff / $p) * 100;

            return [
                'trend' => $diff > 0 ? 'up' : ($diff < 0 ? 'down' : 'stable'),
                'change' => abs(round($pct, 1)) . '%'
            ];
        };

        // Prepare cards data
        $cards = [
            [
                'title' => 'Heart Rate',
                'value' => $latest ? ($latest->heart_rate ?? 'N/A') : 'N/A',
                'unit' => 'bpm',
                'status' => 'Normal', // Logic needed
                'trendData' => $calculateTrend($latest ? $latest->heart_rate : 0, $previous ? $previous->heart_rate : 0)
            ],
            [
                'title' => 'Blood Pressure',
                'value' => $latest ? ($latest->blood_pressure ?? 'N/A') : 'N/A',
                'unit' => 'mmHg',
                'status' => 'Normal',
                'trendData' => ['trend' => 'stable', 'change' => '0%']
            ],
            [
                'title' => 'Body Temperature',
                'value' => $latest ? ($latest->temperature ?? 'N/A') : 'N/A',
                'unit' => '°F',
                'status' => 'Normal',
                'trendData' => $calculateTrend($latest ? $latest->temperature : 0, $previous ? $previous->temperature : 0)
            ],
            [
                'title' => 'Blood Oxygen',
                'value' => $latest ? ($latest->spo2 ?? 'N/A') : 'N/A',
                'unit' => '%',
                'status' => 'Normal',
                'trendData' => $calculateTrend($latest ? $latest->spo2 : 0, $previous ? $previous->spo2 : 0)
            ],
            [
                'title' => 'Weight',
                'value' => $latest ? ($latest->weight ?? 'N/A') : 'N/A',
                'unit' => 'kg',
                'status' => 'Normal',
                'trendData' => $calculateTrend($latest ? $latest->weight : 0, $previous ? $previous->weight : 0)
            ],
        ];

        return response()->json([
            'cards' => $cards,
            'history' => $vitalsHistory->map(function ($v) {
                return [
                    'id' => $v->id,
                    'date' => $v->recorded_at ? $v->recorded_at->format('M d, Y') : '',
                    'heartRate' => $v->heart_rate ? $v->heart_rate . ' bpm' : '-',
                    'bp' => $v->blood_pressure ?? '-',
                    'temp' => $v->temperature ? $v->temperature . '°F' : '-',
                    'weight' => $v->weight ? $v->weight . ' kg' : '-',
                ];
            })
        ]);
    }
}
