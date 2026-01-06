<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PrescriptionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Prescription::class);
        $prescriptions = Prescription::with(['consultation.patient', 'consultation.doctor'])
            ->orderBy('issued_at', 'desc')
            ->paginate(20);
        return view('clinical.prescription.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        Gate::authorize('view', $prescription);
        $prescription->load(['items.medicine', 'consultation.patient', 'consultation.doctor', 'consultation']);
        return view('clinical.prescription.show', compact('prescription'));
    }

    // Usually created via Consultation, but could add standalone create/edit if needed later.
    // For now, view/print is key.

    public function print(Prescription $prescription)
    {
        Gate::authorize('view', $prescription);
        $prescription->load(['items.medicine', 'consultation.patient', 'consultation.doctor']);
        return view('clinical.prescription.print', compact('prescription'));
    }
}
