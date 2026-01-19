<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientSearchController extends Controller
{
    /**
     * Search patients for Select2 via AJAX.
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page', 1);
        $limit = 20;

        $query = Patient::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('patient_code', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $patients = $query->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);

        $results = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => $patient->name . ' (' . ($patient->patient_code ?? 'N/A') . ')',
                'patient' => $patient // Optional: pass full object if needed by frontend
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $patients->hasMorePages()
            ]
        ]);
    }
}
