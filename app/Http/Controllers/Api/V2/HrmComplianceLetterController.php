<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmComplianceLetter;
use Illuminate\Http\Request;

class HrmComplianceLetterController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmComplianceLetter::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%')
                    ->orWhere('subject', 'like', '%'.$search.'%');
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
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $item = HrmComplianceLetter::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'category' => $validated['category'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'body' => $validated['body'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmComplianceLetter $letter)
    {
        $user = $request->user();

        if ($letter->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $letter->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $letter,
        ]);
    }

    public function destroy(Request $request, HrmComplianceLetter $letter)
    {
        $user = $request->user();

        if ($letter->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $letter->status = 'archived';
        $letter->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

