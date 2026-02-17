<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPayrollAllowance;
use Illuminate\Http\Request;

class HrmPayrollAllowanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmPayrollAllowance::query()
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
            'calculation_type' => 'nullable|in:fixed,percent_basic,percent_gross',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $existsByName = HrmPayrollAllowance::where('clinic_id', $user->clinic_id)
            ->where('name', $validated['name'])
            ->exists();

        if ($existsByName) {
            return response()->json(['message' => 'Allowance name already exists'], 422);
        }

        if (! empty($validated['code'])) {
            $existsByCode = HrmPayrollAllowance::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Allowance code already exists'], 422);
            }
        }

        $item = HrmPayrollAllowance::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'calculation_type' => $validated['calculation_type'] ?? 'fixed',
            'amount' => $validated['amount'] ?? 0,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmPayrollAllowance $allowance)
    {
        $user = $request->user();

        if ($allowance->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'calculation_type' => 'nullable|in:fixed,percent_basic,percent_gross',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['name'])) {
            $existsByName = HrmPayrollAllowance::where('clinic_id', $user->clinic_id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $allowance->id)
                ->exists();
            if ($existsByName) {
                return response()->json(['message' => 'Allowance name already exists'], 422);
            }
        }

        if (array_key_exists('code', $validated) && $validated['code'] !== null && $validated['code'] !== '') {
            $existsByCode = HrmPayrollAllowance::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $allowance->id)
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Allowance code already exists'], 422);
            }
        }

        $allowance->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $allowance,
        ]);
    }

    public function destroy(Request $request, HrmPayrollAllowance $allowance)
    {
        $user = $request->user();

        if ($allowance->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $allowance->status = 'inactive';
        $allowance->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

