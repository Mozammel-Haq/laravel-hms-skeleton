<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClinicController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Clinic::class);
        $query = Clinic::query();

        if (request()->filled('trashed')) {
            if (request('trashed') === 'only') {
                $query->onlyTrashed();
            } elseif (request('trashed') === 'with') {
                $query->withTrashed();
            }
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $clinics = $query->latest()->paginate(20)->withQueryString();
        return view('clinics.index', compact('clinics'));
    }

    public function create()
    {
        Gate::authorize('create', Clinic::class);
        return view('clinics.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Clinic::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clinics,code',
            'registration_number' => 'nullable|string|max:100|unique:clinics,registration_number',
            'about' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'image|max:2048',
            'timezone' => 'required|string|max:64',
            'currency' => 'required|string|max:10',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', str_replace(' ', '-', $file->getClientOriginalName()));
            $data['logo_path'] = $file->storeAs('clinics/logos', $filename, 'public');
        }

        $clinic = Clinic::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $filename = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', str_replace(' ', '-', $image->getClientOriginalName()));
                $path = $image->storeAs('clinics/gallery', $filename, 'public');
                $clinic->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index
                ]);
            }
        }

        return redirect()->route('clinics.show', $clinic)->with('success', 'Clinic created successfully.');
    }

    public function show(Clinic $clinic)
    {
        Gate::authorize('view', $clinic);
        return view('clinics.show', compact('clinic'));
    }

    public function edit(Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        // dd($clinic);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clinics,code,' . $clinic->id,
            'registration_number' => 'nullable|string|max:100|unique:clinics,registration_number,' . $clinic->id,
            'about' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'image|max:2048',
            'timezone' => 'required|string|max:64',
            'currency' => 'required|string|max:10',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'status' => 'required|in:active,inactive,suspended',
        ]);
        // dd($data);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($clinic->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($clinic->logo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($clinic->logo_path);
            }
            $file = $request->file('logo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', str_replace(' ', '-', $file->getClientOriginalName()));
            $data['logo_path'] = $file->storeAs('clinics/logos', $filename, 'public');
        }

        $clinic->update($data);

        if ($request->hasFile('gallery')) {
            $currentMaxSortOrder = $clinic->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery') as $index => $image) {
                $filename = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', str_replace(' ', '-', $image->getClientOriginalName()));
                $path = $image->storeAs('clinics/gallery', $filename, 'public');
                $clinic->images()->create([
                    'image_path' => $path,
                    'sort_order' => $currentMaxSortOrder + $index + 1
                ]);
            }
        }

        // Handle reordering if provided
        if ($request->has('gallery_order')) {
            $order = json_decode($request->gallery_order, true);
            if (is_array($order)) {
                foreach ($order as $sortOrder => $imageId) {
                    \App\Models\ClinicImage::where('id', $imageId)
                        ->where('clinic_id', $clinic->id)
                        ->update(['sort_order' => $sortOrder]);
                }
            }
        }

        return redirect()->route('clinics.show', $clinic)->with('success', 'Clinic updated successfully.');
    }

    public function destroyImage(\App\Models\ClinicImage $image)
    {
        $image->load('clinic');
        Gate::authorize('update', $image->clinic);

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    public function destroy(Clinic $clinic)
    {
        Gate::authorize('delete', $clinic);

        try {
            $clinic->delete();
            return redirect()->route('clinics.index')->with('success', 'Clinic deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for integrity constraint violation
            if ($e->getCode() === '23000') {
                return back()->with('error', 'Cannot delete this clinic because it has associated records (e.g., admissions, appointments).');
            }
            return back()->with('error', 'An error occurred while deleting the clinic.');
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred.');
        }
    }

    public function restore($id)
    {
        $clinic = Clinic::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $clinic);

        $clinic->restore();

        return redirect()->route('clinics.index')->with('success', 'Clinic restored successfully.');
    }
}
