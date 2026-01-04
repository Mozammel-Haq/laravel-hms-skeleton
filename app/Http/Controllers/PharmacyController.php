<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacySale;
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
        $sales = PharmacySale::with('patient')->latest()->paginate(20);
        return view('pharmacy.index', compact('sales'));
    }

    public function create()
    {
        Gate::authorize('create', PharmacySale::class);
        $patients = Patient::all();
        $medicines = Medicine::where('stock_quantity', '>', 0)->get();
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
            return redirect()->route('pharmacy.show', $sale)
                ->with('success', 'Sale processed successfully.');
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
}
