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
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Medicine::create($request->only([
            'name',
            'generic_name',
            'manufacturer',
            'strength',
            'dosage_form',
            'price',
            'status',
        ]));

        return redirect()->route('pharmacy.medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function show(Medicine $medicine)
    {
        Gate::authorize('view', $medicine);
        return view('pharmacy.inventory.create', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        Gate::authorize('update', $medicine);
        return view('pharmacy.inventory.create', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        Gate::authorize('update', $medicine);

        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'dosage_form' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $medicine->update($request->only([
            'name',
            'generic_name',
            'manufacturer',
            'strength',
            'dosage_form',
            'price',
            'status',
        ]));

        return redirect()->route('pharmacy.medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        Gate::authorize('delete', $medicine);
        $medicine->delete();
        return redirect()->route('pharmacy.medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
