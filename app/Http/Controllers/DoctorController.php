<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Doctor::class);
        $doctors = Doctor::with(['user', 'department'])
            ->whereHas('clinics', function ($q) {
                $q->where('clinics.id', auth()->user()->clinic_id);
            })
            ->latest()
            ->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        Gate::authorize('create', Doctor::class);
        $departments = Department::all();
        return view('doctors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Doctor::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'primary_department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string',
            'license_number' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'clinic_id' => auth()->user()->clinic_id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]);

            $user->assignRole('Doctor');

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'primary_department_id' => $request->primary_department_id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'status' => 'active',
            ]);

            $doctor->clinics()->syncWithoutDetaching([auth()->user()->clinic_id]);
        });

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        Gate::authorize('view', $doctor);
        $doctor->load(['user', 'department', 'schedules']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        Gate::authorize('update', $doctor);
        $departments = Department::all();
        return view('doctors.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        Gate::authorize('update', $doctor);

        $request->validate([
            'specialization' => 'required|string',
            'license_number' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $doctor->update($request->only(['specialization', 'license_number', 'status']));

        return redirect()->route('doctors.show', $doctor)->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        Gate::authorize('delete', $doctor);
        // Soft delete logic
        $doctor->delete();
        $doctor->user->delete(); // Also delete the user login? Usually yes, or deactivate.

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    // Schedule Management
    public function schedule(Doctor $doctor)
    {
        Gate::authorize('update', $doctor); // Assuming managing schedule requires update permission
        $schedules = $doctor->schedules;
        return view('doctors.schedule', compact('doctor', 'schedules'));
    }

    public function updateSchedule(Request $request, Doctor $doctor)
    {
        Gate::authorize('update', $doctor);

        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.slot_duration_minutes' => 'required|integer|min:5',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            // This is a simplified full replacement strategy
            $doctor->schedules()->delete();

            foreach ($request->schedules as $schedule) {
                $doctor->schedules()->create($schedule + ['clinic_id' => auth()->user()->clinic_id]);
            }
        });

        return back()->with('success', 'Schedule updated successfully.');
    }
}
