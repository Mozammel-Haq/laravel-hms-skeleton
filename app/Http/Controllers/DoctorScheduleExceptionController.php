<?php

namespace App\Http\Controllers;

use App\Models\DoctorScheduleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DoctorScheduleExceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->doctor) {
            abort(403, 'User is not a doctor');
        }

        $query = $user->doctor->exceptions()
            ->with('clinic');

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->where('status', request('status'));
            }
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('reason', 'like', "%{$search}%");
        }

        if (request()->filled('from')) {
            $query->whereDate('start_date', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('start_date', '<=', request('to'));
        }

        $exceptions = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('doctors.schedule.exceptions.index', compact('exceptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->doctor) {
            abort(403, 'User is not a doctor');
        }

        return view('doctors.schedule.exceptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->doctor) {
            abort(403, 'User is not a doctor');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_available' => 'boolean',
            'start_time' => 'nullable|required_if:is_available,1|date_format:H:i',
            'end_time' => 'nullable|required_if:is_available,1|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:255',
        ]);

        $doctor = $user->doctor;

        Log::info('Schedule exception requested', [
            'doctor_id' => $doctor->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason
        ]);

        // Check for overlapping exceptions
        $exists = $doctor->exceptions()
            ->where('clinic_id', $user->clinic_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($exists) {
            Log::warning('Duplicate exception prevented', [
                'doctor_id' => $doctor->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);
            return back()->withErrors(['start_date' => 'An exception already exists for this date range.'])->withInput();
        }

        $exception = $doctor->exceptions()->create([
            'clinic_id' => $user->clinic_id, // Current clinic context
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_available' => $request->has('is_available') ? $request->is_available : false,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending', // Requires admin approval
        ]);

        Log::info('Schedule exception created successfully', ['exception_id' => $exception->id]);

        return redirect()->route('doctor.schedule.exceptions.index')
            ->with('success', 'Schedule exception requested successfully. Waiting for approval.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorScheduleException $exception)
    {
        $user = Auth::user();
        if (!$user->doctor || $exception->doctor_id !== $user->doctor->id) {
            abort(403);
        }

        if ($exception->status !== 'pending') {
            return back()->with('error', 'Cannot delete processed exceptions.');
        }

        Log::info('Schedule exception cancelled', ['exception_id' => $exception->id, 'doctor_id' => $user->doctor->id]);

        $exception->delete();

        return back()->with('success', 'Exception request cancelled.');
    }
}
