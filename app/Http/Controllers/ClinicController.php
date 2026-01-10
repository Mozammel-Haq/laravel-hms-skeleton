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
        $clinics = Clinic::orderBy('name')->paginate(20);
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
            'registration_number' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo_path' => 'nullable|string|max:255',
            'timezone' => 'required|string|max:64',
            'currency' => 'required|string|max:10',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $clinic = Clinic::create($data);

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
            'registration_number' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo_path' => 'nullable|string|max:255',
            'timezone' => 'required|string|max:64',
            'currency' => 'required|string|max:10',
            'opening_time' => 'nullable|date_format:H:i:s',
            'closing_time' => 'nullable|date_format:H:i:s',
            'status' => 'required|in:active,inactive,suspended',
        ]);
        // dd($data);
        $clinic->update($data);

        return redirect()->route('clinics.show', $clinic)->with('success', 'Clinic updated successfully.');
    }

    public function destroy(Clinic $clinic)
    {
        Gate::authorize('delete', $clinic);
        $clinic->delete();
        return redirect()->route('clinics.index')->with('success', 'Clinic deleted successfully.');
    }
}
