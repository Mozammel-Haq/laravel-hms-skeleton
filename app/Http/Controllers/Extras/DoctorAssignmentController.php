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

        $query = Doctor::with(['user', 'clinics', 'primaryDepartment', 'schedules']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                ->orWhere('specialization', 'like', "%{$search}%")
                ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if (request()->filled('clinic_id')) {
            $clinicId = request('clinic_id');
            $query->whereHas('clinics', function ($q) use ($clinicId) {
                $q->where('clinics.id', $clinicId);
            });
        }

        if (request()->filled('status')) {
            if (request('status') === 'deleted') {
                $query->onlyTrashed();
            }
        }

        $doctors = $query->latest()->paginate(20)->withQueryString();

        return view('doctors.assignment', compact('clinics', 'doctors'));
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
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $validated = $request->validate([
            'clinic_ids' => 'nullable|array',
            'clinic_ids.*' => 'exists:clinics,id',
        ]);

        if ($user->hasRole('Super Admin')) {
            $doctor->clinics()->sync($validated['clinic_ids'] ?? []);
        } elseif ($user->hasRole('Clinic Admin')) {
            $clinicId = $user->clinic_id;
            $clinicIds = $validated['clinic_ids'] ?? [];
            $shouldAttach = in_array($clinicId, $clinicIds);

            if ($shouldAttach) {
                $doctor->clinics()->syncWithoutDetaching([$clinicId]);
            } else {
                $doctor->clinics()->detach($clinicId);
            }
        } else {
            abort(403);
        }

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
