<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmCompliancePolicy;
use Illuminate\Http\Request;

class HrmCompliancePolicyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmCompliancePolicy::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('effective_from')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
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
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'effective_from' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $item = HrmCompliancePolicy::create([
            'clinic_id' => $user->clinic_id,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'effective_from' => $validated['effective_from'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmCompliancePolicy $policy)
    {
        $user = $request->user();

        if ($policy->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'effective_from' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $policy->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $policy,
        ]);
    }

    public function destroy(Request $request, HrmCompliancePolicy $policy)
    {
        $user = $request->user();

        if ($policy->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $policy->status = 'archived';
        $policy->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

