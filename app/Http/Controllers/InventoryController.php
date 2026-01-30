<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Manages medicine batches and inventory stock.
 *
 * Responsibilities:
 * - Batch management (creation, listing)
 * - Stock level tracking per clinic
 * - Expiry tracking and status filtering
 */
class InventoryController extends Controller
{
    /**
     * Display a listing of medicine batches.
     *
     * Supports filtering by:
     * - Status: 'expired', 'out_of_stock', 'in_stock'
     * - Search: Batch number, Medicine name
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // View inventory for the current clinic
        $query = MedicineBatch::with('medicine')
            ->where('clinic_id', auth()->user()->clinic_id);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                    ->orWhereHas('medicine', function ($m) use ($search) {
                        $m->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (request()->filled('status')) {
            if (request('status') === 'expired') {
                $query->whereDate('expiry_date', '<', now());
            } elseif (request('status') === 'out_of_stock') {
                $query->where('quantity_in_stock', 0);
            } elseif (request('status') === 'in_stock') {
                $query->where('quantity_in_stock', '>', 0)
                    ->whereDate('expiry_date', '>=', now());
            }
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }
        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $batches = $query->latest()->paginate(20)->withQueryString();

        return view('pharmacy.inventory.batches', compact('batches'));
    }

    /**
     * Show the form for adding a new batch.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Gate::authorize('create', MedicineBatch::class); // Needs policy
        $medicines = Medicine::all();
        return view('pharmacy.inventory.add-batch', compact('medicines'));
    }

    /**
     * Store a newly created batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
