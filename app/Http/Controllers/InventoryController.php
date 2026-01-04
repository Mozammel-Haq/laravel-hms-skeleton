<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    public function index()
    {
        // View inventory for the current clinic
        $batches = MedicineBatch::with('medicine')
            ->where('clinic_id', auth()->user()->clinic_id)
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('expiry_date')
            ->paginate(20);

        return view('pharmacy.inventory.batches', compact('batches'));
    }

    public function create()
    {
        Gate::authorize('create', MedicineBatch::class); // Needs policy
        $medicines = Medicine::all();
        return view('pharmacy.inventory.add-batch', compact('medicines'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', MedicineBatch::class);

        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_number' => 'required|string|max:255',
            'expiry_date' => 'required|date|after:today',
            'quantity_in_stock' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        MedicineBatch::create($request->all() + ['clinic_id' => auth()->user()->clinic_id]);

        return redirect()->route('pharmacy.inventory.index')->with('success', 'Batch added to inventory.');
    }
}
