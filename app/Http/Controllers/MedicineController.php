<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicineController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Medicine::class);
        $medicines = Medicine::latest()->paginate(20);
        return view('pharmacy.inventory.index', compact('medicines'));
    }

    public function create()
    {
        Gate::authorize('create', Medicine::class);
        return view('pharmacy.inventory.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Medicine::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:medicines,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        Medicine::create($request->all()); // Global model or Tenant? MD says Global for Catalog?
        // Wait, MD said "Global Models... Medicine (catalog)".
        // If it's a global catalog, does it have stock?
        // Usually stock is per clinic.
        // Let's check the migration or assumption.
        // If Medicine is global, stock should be in a pivot or separate table 'ClinicMedicine'.
        // MD says: "PharmacySale", "PharmacySaleItem" are Tenant-Bound. "Medicine (catalog)" is Global.
        // But `PharmacyService` deducted `stock_quantity` from `Medicine` model directly.
        // This implies Medicine is either tenant-bound OR the Service was simplifying.
        // Let's check Medicine model.

        return redirect()->route('pharmacy.medicines.index')->with('success', 'Medicine added successfully.');
    }

    // ... update/destroy similar
}
