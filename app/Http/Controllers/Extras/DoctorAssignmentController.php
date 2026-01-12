<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DoctorAssignmentController extends Controller
{
    public function index()
    {
        Gate::authorize('view_doctors'); // adjust as per your policies

        $clinics = Clinic::orderBy('name')->get();
        $clinicId = session('selected_clinic_id') ?? auth()->user()->clinic_id;
        $doctors = Clinic::with(['doctors.user', 'doctors.schedules'])
            ->find($clinicId);

        return view('doctor_assignments.index', compact('clinics', 'doctors'));
    }

    public function edit(Doctor $doctor)
    {
        Gate::authorize('update', $doctor);

        $clinics = Clinic::orderBy('name')->get();
        $assignedClinicIds = $doctor->clinics->pluck('id')->toArray();

        return view('doctor_assignments.edit', compact('doctor', 'clinics', 'assignedClinicIds'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        // Permission requirement: Only Super Admins can assign clinics to doctors
        if (!auth()->user() || !auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can manage doctor clinic assignments.');
        }

        Gate::authorize('update', $doctor);

        $validated = $request->validate([
            'clinic_ids' => 'nullable|array',
            'clinic_ids.*' => 'exists:clinics,id',
        ]);

        $doctor->clinics()->sync($validated['clinic_ids'] ?? []);

        return redirect()->route('doctors.assignment')
            ->with('success', 'Doctor clinic assignments updated successfully.');
    }

    // Optional: API for calendar (if needed)
    public function calendar(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        // Fetch doctors and schedules (simplified example)
        $clinicId = session('selected_clinic_id') ?? auth()->user()->clinic_id;
        $doctors = Clinic::with(['doctors.user', 'doctors.schedules'])->find($clinicId)->doctors ?? [];

        $calendarData = [];

        foreach ($doctors as $doctor) {
            foreach ($doctor->schedules as $schedule) {
                $calendarData[$doctor->id][] = [
                    'date' => $schedule->schedule_date,
                    'start' => $schedule->start_time,
                    'end' => $schedule->end_time,
                ];
            }
        }

        return response()->json($calendarData);
    }
}
