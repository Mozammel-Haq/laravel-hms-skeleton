<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmComplianceDocument;
use Illuminate\Http\Request;

class HrmComplianceDocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmComplianceDocument::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%')
                    ->orWhere('document_type', 'like', '%'.$search.'%');
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
            'document_type' => 'nullable|string|max:100',
            'storage_path' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
            'published_at' => 'nullable|date',
        ]);

        $item = HrmComplianceDocument::create([
            'clinic_id' => $user->clinic_id,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'document_type' => $validated['document_type'] ?? null,
            'storage_path' => $validated['storage_path'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'draft',
            'published_at' => $validated['published_at'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmComplianceDocument $document)
    {
        $user = $request->user();

        if ($document->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'document_type' => 'nullable|string|max:100',
            'storage_path' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
            'published_at' => 'nullable|date',
        ]);

        $document->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $document,
        ]);
    }

    public function destroy(Request $request, HrmComplianceDocument $document)
    {
        $user = $request->user();

        if ($document->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $document->status = 'archived';
        $document->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

