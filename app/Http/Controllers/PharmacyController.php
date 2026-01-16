<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacySale;
use App\Models\Prescription;
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

    public function create()
    {
        Gate::authorize('create', PharmacySale::class);
        $patients = Patient::orderBy('name')->get();
        $medicines = Medicine::whereHas('batches', function ($q) {
            $q->where('clinic_id', auth()->user()->clinic_id)
                ->where('quantity_in_stock', '>', 0);
        })->orderBy('name')->get();
        return view('pharmacy.pos', compact('patients', 'medicines'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', PharmacySale::class);

        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'patient_id' => 'required|exists:patients,id',
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
                discount: 0,
                tax: 0,
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

    public function show(PharmacySale $pharmacySale) // Implicit binding might fail if param name differs
    {
        // Parameter name in route is likely 'pharmacy' or 'pharmacy_sale'
        // Let's assume standard resource naming
        Gate::authorize('view', $pharmacySale);
        $pharmacySale->load(['patient']);
        return view('pharmacy.show', ['sale' => $pharmacySale]);
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
