<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class MedicineController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Medicine::class);

        $query = Medicine::query();

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } elseif (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('generic_name', 'like', '%' . $search . '%')
                    ->orWhere('manufacturer', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $medicines = $query->latest()->paginate(20)->withQueryString();
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

    public function search(Request $request)
    {
        try {
            Gate::authorize('viewAny', Medicine::class);
            $term = trim((string) $request->get('term', ''));
            $clinicId = optional(auth()->user())->clinic_id;

            if (!$clinicId || !Schema::hasTable('medicine_batches')) {
                return response()->json([
                    'results' => [],
                    'pagination' => ['more' => false],
                ]);
            }

            $quantityColumn = null;

            if (Schema::hasColumn('medicine_batches', 'quantity_in_stock')) {
                $quantityColumn = 'quantity_in_stock';
            } elseif (Schema::hasColumn('medicine_batches', 'quantity')) {
                $quantityColumn = 'quantity';
            }

            $query = Medicine::query()
                ->where('status', 'active')
                ->whereHas('batches', function ($q) use ($clinicId, $quantityColumn) {
                    $q->where('clinic_id', $clinicId);
                    if ($quantityColumn) {
                        $q->where($quantityColumn, '>', 0);
                    }
                });

            if ($quantityColumn) {
                $query->withSum(['batches as stock' => function ($q) use ($clinicId, $quantityColumn) {
                    $q->where('clinic_id', $clinicId);
                }], $quantityColumn);
            }

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                        ->orWhere('generic_name', 'like', '%' . $term . '%')
                        ->orWhere('manufacturer', 'like', '%' . $term . '%');
                });
            }

            $items = $query->orderBy('name')->limit(20)->get()->map(function ($m) use ($quantityColumn) {
                $stock = 0;

                if ($quantityColumn && isset($m->stock)) {
                    $stock = (int) $m->stock;
                }

                $label = $m->name;

                if (!empty($m->strength)) {
                    $label .= ' (' . $m->strength . ')';
                }

                $label .= ' — Stock: ' . $stock;

                return [
                    'id' => $m->id,
                    'text' => $label,
                    'price' => $m->price,
                    'stock' => $stock,
                ];
            });

            return response()->json([
                'results' => $items,
                'pagination' => ['more' => false],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'results' => [],
                'pagination' => ['more' => false],
            ]);
        }
    }
}
