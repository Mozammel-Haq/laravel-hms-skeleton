<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmSalaryStructure;
use Illuminate\Http\Request;

class HrmSalaryStructureController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $items = HrmSalaryStructure::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('name')
            ->get();

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
            'basic_amount' => 'nullable|numeric|min:0',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $existsByName = HrmSalaryStructure::where('clinic_id', $user->clinic_id)
            ->where('name', $validated['name'])
            ->exists();

        if ($existsByName) {
            return response()->json(['message' => 'Structure name already exists'], 422);
        }

        if (! empty($validated['code'])) {
            $existsByCode = HrmSalaryStructure::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Structure code already exists'], 422);
            }
        }

        if (! empty($validated['is_default'])) {
            HrmSalaryStructure::where('clinic_id', $user->clinic_id)->update(['is_default' => false]);
        }

        $item = HrmSalaryStructure::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'basic_amount' => $validated['basic_amount'] ?? 0,
            'is_default' => $validated['is_default'] ?? false,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmSalaryStructure $structure)
    {
        $user = $request->user();

        if ($structure->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'basic_amount' => 'nullable|numeric|min:0',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['name'])) {
            $existsByName = HrmSalaryStructure::where('clinic_id', $user->clinic_id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $structure->id)
                ->exists();
            if ($existsByName) {
                return response()->json(['message' => 'Structure name already exists'], 422);
            }
        }

        if (array_key_exists('code', $validated) && $validated['code'] !== null && $validated['code'] !== '') {
            $existsByCode = HrmSalaryStructure::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $structure->id)
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Structure code already exists'], 422);
            }
        }

        if (array_key_exists('is_default', $validated) && $validated['is_default']) {
            HrmSalaryStructure::where('clinic_id', $user->clinic_id)
                ->where('id', '!=', $structure->id)
                ->update(['is_default' => false]);
        }

        $structure->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $structure,
        ]);
    }

    public function destroy(Request $request, HrmSalaryStructure $structure)
    {
        $user = $request->user();

        if ($structure->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $structure->status = 'inactive';
        $structure->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

