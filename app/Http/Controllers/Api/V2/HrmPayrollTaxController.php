<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPayrollTax;
use Illuminate\Http\Request;

class HrmPayrollTaxController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmPayrollTax::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('name');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $items = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'calculation_type' => 'nullable|in:flat,percent',
            'rate' => 'nullable|numeric|min:0',
            'threshold' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $existsByName = HrmPayrollTax::where('clinic_id', $user->clinic_id)
            ->where('name', $validated['name'])
            ->exists();

        if ($existsByName) {
            return response()->json(['message' => 'Tax name already exists'], 422);
        }

        if (! empty($validated['code'])) {
            $existsByCode = HrmPayrollTax::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Tax code already exists'], 422);
            }
        }

        $item = HrmPayrollTax::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'calculation_type' => $validated['calculation_type'] ?? 'percent',
            'rate' => $validated['rate'] ?? 0,
            'threshold' => $validated['threshold'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmPayrollTax $tax)
    {
        $user = $request->user();

        if ($tax->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'calculation_type' => 'nullable|in:flat,percent',
            'rate' => 'nullable|numeric|min:0',
            'threshold' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['name'])) {
            $existsByName = HrmPayrollTax::where('clinic_id', $user->clinic_id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $tax->id)
                ->exists();
            if ($existsByName) {
                return response()->json(['message' => 'Tax name already exists'], 422);
            }
        }

        if (array_key_exists('code', $validated) && $validated['code'] !== null && $validated['code'] !== '') {
            $existsByCode = HrmPayrollTax::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $tax->id)
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Tax code already exists'], 422);
            }
        }

        $tax->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $tax,
        ]);
    }

    public function destroy(Request $request, HrmPayrollTax $tax)
    {
        $user = $request->user();

        if ($tax->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tax->status = 'inactive';
        $tax->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

