<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPerformanceKpi;
use Illuminate\Http\Request;

class HrmPerformanceKpiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmPerformanceKpi::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('name');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
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

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'frequency' => 'nullable|in:monthly,quarterly,annually',
            'weight' => 'nullable|integer|min:0|max:100',
            'target_role' => 'nullable|string|max:100',
            'target_department_id' => 'nullable|integer|exists:departments,id',
            'target_user_id' => 'nullable|integer|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,inactive,archived',
        ]);

        $item = HrmPerformanceKpi::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'category' => $validated['category'] ?? null,
            'frequency' => $validated['frequency'] ?? 'annually',
            'weight' => $validated['weight'] ?? 0,
            'target_role' => $validated['target_role'] ?? null,
            'target_department_id' => $validated['target_department_id'] ?? null,
            'target_user_id' => $validated['target_user_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmPerformanceKpi $performanceKpi)
    {
        $user = $request->user();

        if ($performanceKpi->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'frequency' => 'nullable|in:monthly,quarterly,annually',
            'weight' => 'nullable|integer|min:0|max:100',
            'target_role' => 'nullable|string|max:100',
            'target_department_id' => 'nullable|integer|exists:departments,id',
            'target_user_id' => 'nullable|integer|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,inactive,archived',
        ]);

        $performanceKpi->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $performanceKpi,
        ]);
    }

    public function destroy(Request $request, HrmPerformanceKpi $performanceKpi)
    {
        $user = $request->user();

        if ($performanceKpi->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $performanceKpi->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

