<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Doctor::class);

        $query = Doctor::with(['user', 'department'])
            ->whereHas('clinics', function ($q) {
                $q->where('clinics.id', auth()->user()->clinic_id);
            });

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } elseif (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('department', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhere('specialization', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('consultation_room_number', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $doctors = $query->latest()->paginate(15)->withQueryString();

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
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8',
            'primary_department_id' => 'required|exists:departments,id',
            'specialization' => 'required|array',
            'specialization.*' => 'string',
            'license_number' => 'required|string|unique:doctors,license_number',
            'registration_number' => 'nullable|string|max:255|unique:doctors,registration_number',
            'experience_years' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'date_of_birth' => 'nullable|date',
            'consultation_fee' => 'nullable|numeric|min:0',
            'follow_up_fee' => 'nullable|numeric|min:0',
            'biography' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'consultation_room_number' => 'nullable|string|max:50',
            'consultation_floor' => 'nullable|string|max:50',
            'is_featured' => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $photoPath = null;

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('assets/img/doctors');

                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $photoPath = 'assets/img/doctors/' . $filename;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
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
                'registration_number' => $request->registration_number,
                'experience_years' => $request->experience_years ?? 0,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'date_of_birth' => $request->date_of_birth,
                'consultation_fee' => $request->consultation_fee,
                'follow_up_fee' => $request->follow_up_fee,
                'biography' => $request->biography,
                'profile_photo' => $photoPath,
                'consultation_room_number' => $request->consultation_room_number,
                'consultation_floor' => $request->consultation_floor,
                'is_featured' => $request->boolean('is_featured', false),
                'status' => 'active',
            ]);

            $doctor->clinics()->syncWithoutDetaching([auth()->user()->clinic_id]);
        });

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        Gate::authorize('view', $doctor);

        $doctor->load([
            'user',
            'department',
            'schedules',
            'educations',
            'awards',
            'certifications',
        ]);

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
            'phone' => 'nullable|string|max:30',
            'primary_department_id' => 'required|exists:departments,id',
            'specialization' => 'required|array',
            'specialization.*' => 'string',
            'license_number' => 'required|string|unique:doctors,license_number,' . $doctor->id,
            'registration_number' => 'nullable|string|max:255|unique:doctors,registration_number,' . $doctor->id,
            'experience_years' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'date_of_birth' => 'nullable|date',
            'consultation_fee' => 'nullable|numeric|min:0',
            'follow_up_fee' => 'nullable|numeric|min:0',
            'biography' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'consultation_room_number' => 'nullable|string|max:50',
            'consultation_floor' => 'nullable|string|max:50',
            'is_featured' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $photoPath = $doctor->profile_photo;

        if ($request->filled('phone') || $request->has('phone')) {
            if ($doctor->user) {
                $doctor->user->update([
                    'phone' => $request->phone,
                ]);
            }
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/img/doctors');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $photoPath = 'assets/img/doctors/' . $filename;
        }

        $doctor->update([
            'primary_department_id' => $request->primary_department_id,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'registration_number' => $request->registration_number,
            'experience_years' => $request->experience_years ?? $doctor->experience_years,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'date_of_birth' => $request->date_of_birth,
            'consultation_fee' => $request->consultation_fee,
            'follow_up_fee' => $request->follow_up_fee,
            'biography' => $request->biography,
            'profile_photo' => $photoPath,
            'consultation_room_number' => $request->consultation_room_number,
            'consultation_floor' => $request->consultation_floor,
            'is_featured' => $request->boolean('is_featured', $doctor->is_featured),
            'status' => $request->status,
        ]);

        return redirect()->route('doctors.show', $doctor)->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        Gate::authorize('delete', $doctor);

        try {
            DB::transaction(function () use ($doctor) {
                $doctor->update(['status' => 'inactive']);
                $doctor->delete();

                if ($doctor->user) {
                    $doctor->user->update(['status' => 'inactive']);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('doctors.index')->with('error', 'Cannot delete doctor because they have associated records. Please remove related records first.');
            }
            throw $e;
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    public function restore($id)
    {
        $doctor = Doctor::withTrashed()->findOrFail($id);

        Gate::authorize('delete', $doctor);

        DB::transaction(function () use ($doctor) {
            $doctor->restore();
            $doctor->update(['status' => 'active']);

            if ($doctor->user) {
                $doctor->user->restore();
                $doctor->user->update(['status' => 'active']);
            }
        });

        return redirect()->route('doctors.index')->with('success', 'Doctor restored successfully.');
    }

    // Schedule Management
    public function schedule(Doctor $doctor)
    {
        Gate::authorize('update', $doctor); // Assuming managing schedule requires update permission

        // Only fetch schedules for the current clinic context
        $schedules = $doctor->schedules()
            ->where('clinic_id', auth()->user()->clinic_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('doctors.schedule', compact('doctor', 'schedules'));
    }

    public function updateSchedule(Request $request, Doctor $doctor)
    {
        Gate::authorize('update', $doctor);

        $clinic = auth()->user()->clinic;

        Log::info('Schedule update initiated', [
            'doctor_id' => $doctor->id,
            'clinic_id' => auth()->user()->clinic_id,
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'schedules' => 'nullable|array',
            'schedules.*.type' => 'required|in:weekly,date',
            'schedules.*.day_of_week' => 'nullable|required_if:schedules.*.type,weekly|integer|between:0,6',
            'schedules.*.schedule_date' => 'nullable|required_if:schedules.*.type,date|date|after_or_equal:today',
            'schedules.*.start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($clinic) {
                    if ($clinic && $clinic->opening_time) {
                        $start = Carbon::parse($value);
                        $clinicOpen = Carbon::parse($clinic->opening_time);
                        if ($start->lt($clinicOpen)) {
                            $fail('Schedule start time must be on or after clinic opening time (' . $clinic->opening_time . ').');
                        }
                    }
                },
            ],
            'schedules.*.end_time' => [
                'required',
                'date_format:H:i',
                'after:schedules.*.start_time',
                function ($attribute, $value, $fail) use ($clinic) {
                    if ($clinic && $clinic->closing_time) {
                        $end = Carbon::parse($value);
                        $clinicClose = Carbon::parse($clinic->closing_time);
                        if ($end->gt($clinicClose)) {
                            $fail('Schedule end time must be on or before clinic closing time (' . $clinic->closing_time . ').');
                        }
                    }
                },
            ],
            'schedules.*.slot_duration_minutes' => 'required|integer|min:5',
        ]);

        try {
            DB::transaction(function () use ($request, $doctor) {
                // Only delete schedules for the current clinic
                $deleted = $doctor->schedules()
                    ->where('clinic_id', auth()->user()->clinic_id)
                    ->delete();

                Log::info('Existing schedules cleared', ['count' => $deleted]);

                if ($request->schedules) {
                    foreach ($request->schedules as $schedule) {
                        $data = [
                            'clinic_id' => auth()->user()->clinic_id,
                            'department_id' => $doctor->primary_department_id,
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'slot_duration_minutes' => $schedule['slot_duration_minutes'],
                        ];

                        if ($schedule['type'] === 'weekly') {
                            $data['day_of_week'] = $schedule['day_of_week'];
                        } else {
                            $data['schedule_date'] = $schedule['schedule_date'];
                        }

                        $doctor->schedules()->create($data);
                    }
                    Log::info('New schedules created', ['count' => count($request->schedules)]);
                }
            });

            Log::info('Schedule update completed successfully', ['doctor_id' => $doctor->id]);
            return back()->with('success', 'Schedule updated successfully.');
        } catch (\Exception $e) {
            Log::error('Schedule update failed', [
                'doctor_id' => $doctor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update schedule. Please try again.');
        }
    }
}
