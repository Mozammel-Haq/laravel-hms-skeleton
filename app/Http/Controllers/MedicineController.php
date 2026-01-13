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

    public function search(Request $request)
    {
        Gate::authorize('viewAny', Medicine::class);
        $term = trim((string) $request->get('term', ''));
        $clinicId = optional(auth()->user())->clinic_id;

        $query = Medicine::query()
            ->select('medicines.*')
            ->join('medicine_batches as mb', 'mb.medicine_id', '=', 'medicines.id')
            ->where('mb.clinic_id', $clinicId)
            ->where('mb.quantity_in_stock', '>', 0)
            ->where('medicines.status', 'active')
            ->groupBy(
                'medicines.id',
                'medicines.name',
                'medicines.generic_name',
                'medicines.manufacturer',
                'medicines.strength',
                'medicines.dosage_form',
                'medicines.price',
                'medicines.status',
                'medicines.created_at',
                'medicines.updated_at'
            )
            ->selectRaw('SUM(mb.quantity_in_stock) as stock');

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('medicines.name', 'like', '%' . $term . '%')
                    ->orWhere('medicines.generic_name', 'like', '%' . $term . '%')
                    ->orWhere('medicines.manufacturer', 'like', '%' . $term . '%');
            });
        }

        $items = $query->orderBy('medicines.name')->limit(20)->get()->map(function ($m) {
            $label = $m->name;
            if (!empty($m->strength)) {
                $label .= ' (' . $m->strength . ')';
            }
            $label .= ' — Stock: ' . (int) $m->stock;
            return [
                'id' => $m->id,
                'text' => $label,
                'price' => $m->price,
                'stock' => (int) $m->stock,
            ];
        });

        return response()->json([
            'results' => $items,
            'pagination' => ['more' => false],
        ]);
    }
}
