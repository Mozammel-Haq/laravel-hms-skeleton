<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Visit;
use App\Services\AppointmentService;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VisitController extends Controller
{
    public function index()
    {
        $query = Visit::with(['appointment.patient', 'consultation']);

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } else {
            if (request()->filled('status')) {
                $query->where('visit_status', request('status'));
            }
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('appointment.patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('patient_code', 'like', '%' . $search . '%');
                    });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $visits = $query->paginate(20)->withQueryString();
        return view('visits.index', compact('visits'));
    }

    /**
     * Remove the specified visit from storage (Soft Delete).
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Visit $visit)
    {
        Gate::authorize('delete', $visit);
        $visit->delete();
        return redirect()->route('visits.index')->with('success', 'Visit deleted successfully.');
    }

    public function restore($id)
    {
        $visit = Visit::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $visit);
        $visit->restore();
        return redirect()->route('visits.index')->with('success', 'Visit restored successfully.');
    }

    /**
     * Display the specified visit details.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\View\View
     */
    public function show(Visit $visit)
    {
        $visit->load(['appointment.patient', 'consultation']);
        return view('visits.show', compact('visit'));
    }

    /**
     * Create an invoice for a procedure performed during the visit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visit  $visit
     * @return void
     */
    public function storeProcedureInvoice(Request $request, Visit $visit)
    {
        Gate::authorize('create', \App\Models\Invoice::class);
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);
        $patient = $visit->appointment->patient;
        app(BillingService::class)->createInvoice(
            $patient,
            [[
                'item_type' => 'service',
                'reference_id' => null,
                'description' => $data['description'],
                'quantity' => (int)$data['quantity'],
                'unit_price' => (float)$data['unit_price'],
            ]],
            $visit->appointment_id,
            discount: (float)($data['discount'] ?? 0),
            tax: (float)($data['tax'] ?? 0),
            visitId: $visit->id,
            invoiceType: 'procedure',
            createdBy: auth()->id(),
            finalize: true
        );
        return redirect()->route('visits.show', $visit)->with('success', 'Procedure invoice generated.');
    }

    public function create()
    {
        Gate::authorize('create', Visit::class);
        $appointments = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->with(['patient', 'doctor'])
            ->latest()
            ->take(50)
            ->get();
        return view('visits.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Visit::class);
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $appointment = Appointment::findOrFail($data['appointment_id']);

        $visit = DB::transaction(function () use ($appointment, $data) {
            $visit = Visit::where('appointment_id', $appointment->id)->latest()->first();
            if (!$visit) {
                $visit = Visit::create([
                    'appointment_id' => $appointment->id,
                    'check_in_time' => now(),
                    'visit_status' => 'in_progress',
                ]);
            }

            $consultation = $visit->consultation;
            if (!$consultation) {
                $consultation = Consultation::create([
                    'visit_id' => $visit->id,
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                ]);
                $visit->consultation_id = $consultation->id;
                $visit->save();
            }

            // Generate consultation invoice (finalized) for pre-payment
            $feeInfo = app(AppointmentService::class)->calculateFee($appointment->doctor, $appointment->patient_id);
            $items = [[
                'item_type' => 'consultation',
                'reference_id' => $consultation->id,
                'description' => ($feeInfo['type'] ?? 'new') === 'follow_up' ? 'Consultation Fee (Follow-up)' : 'Consultation Fee (Initial)',
                'quantity' => 1,
                'unit_price' => $feeInfo['fee'] ?? 0,
            ]];
            app(BillingService::class)->createInvoice(
                $appointment->patient,
                $items,
                $appointment->id,
                discount: (float)($data['discount'] ?? 0),
                tax: (float)($data['tax'] ?? 0),
                visitId: $visit->id,
                invoiceType: 'consultation',
                createdBy: auth()->id(),
                finalize: true
            );

            return $visit;
        });

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visit started. Consultation fee invoice generated. Please collect payment.');
    }
}
