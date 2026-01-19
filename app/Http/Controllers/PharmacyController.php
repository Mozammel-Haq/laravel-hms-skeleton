<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacySale;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Services\BillingService;
use App\Services\PharmacyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PharmacyController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    public function index()
    {
        Gate::authorize('viewAny', PharmacySale::class);
        $query = PharmacySale::with('patient');

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } else {
            $query->latest();
        }

        $sales = $query->paginate(20);
        return view('pharmacy.index', compact('sales'));
    }

    public function create(Request $request)
    {
        Gate::authorize('create', PharmacySale::class);
        $patients = collect(); // Use AJAX search
        $medicines = Medicine::whereHas('batches', function ($q) {
            $q->where('clinic_id', auth()->user()->clinic_id)
                ->where('quantity_in_stock', '>', 0);
        })->orderBy('name')->get();
        $prescription = null;
        if ($request->filled('prescription_id')) {
            $prescription = Prescription::with(['items.medicine', 'consultation.patient'])->find($request->input('prescription_id'));
        }

        // Pre-fill patient for Select2
        $targetPatientId = old('patient_id');
        if (!$targetPatientId && $prescription && $prescription->consultation && $prescription->consultation->patient) {
            $targetPatientId = $prescription->consultation->patient->id;
        }

        if ($targetPatientId) {
            $p = Patient::find($targetPatientId);
            if ($p) {
                $patients->push($p);
            }
        }

        return view('pharmacy.pos', compact('patients', 'medicines', 'prescription'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', PharmacySale::class);

        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'patient_id' => 'required|exists:patients,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        try {
            $sale = $this->pharmacyService->processSale($patient, $request->items, (int)$request->prescription_id);

            // Create a separate Pharmacy invoice linked to the OPD visit (if possible)
            $prescription = Prescription::find((int)$request->prescription_id);
            $visitId = optional($prescription?->consultation)->visit_id;
            $appointmentId = optional($prescription?->consultation?->visit)->appointment_id;
            $items = collect($request->items)->map(function ($it) {
                $medicine = Medicine::find($it['medicine_id']);
                return [
                    'item_type' => 'medicine',
                    'reference_id' => $medicine?->id,
                    'description' => $medicine?->name ?? 'Medicine',
                    'quantity' => (int)$it['quantity'],
                    'unit_price' => (float)($medicine?->price ?? 0),
                ];
            })->all();
            app(BillingService::class)->createInvoice(
                $patient,
                $items,
                $appointmentId,
                discount: (float)$request->input('discount', 0),
                tax: (float)$request->input('tax', 0),
                visitId: $visitId,
                invoiceType: 'pharmacy',
                createdBy: auth()->id(),
                finalize: true
            );

            return redirect()->route('pharmacy.show', $sale)
                ->with('success', 'Sale processed and invoice generated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(PharmacySale $pharmacySale)
    {
        Gate::authorize('view', $pharmacySale);
        $pharmacySale->load(['patient', 'items.medicine', 'prescription.consultation.visit']);
        $invoice = null;
        $visitId = optional(optional($pharmacySale->prescription)->consultation)->visit_id;
        $query = Invoice::where('patient_id', $pharmacySale->patient_id)
            ->where('invoice_type', 'pharmacy');
        if ($visitId) {
            $query->where('visit_id', $visitId);
        }
        $invoice = $query->orderByDesc('issued_at')->first();
        return view('pharmacy.show', ['sale' => $pharmacySale, 'invoice' => $invoice]);
    }

    public function destroy(PharmacySale $pharmacySale)
    {
        Gate::authorize('delete', $pharmacySale);
        $pharmacySale->delete();
        return redirect()->route('pharmacy.index')->with('success', 'Sale deleted successfully.');
    }

    public function restore($id)
    {
        $sale = PharmacySale::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $sale);
        $sale->restore();
        return redirect()->route('pharmacy.index')->with('success', 'Sale restored successfully.');
    }
}
