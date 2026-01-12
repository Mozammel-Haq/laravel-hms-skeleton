<?php

namespace App\Http\Controllers;

use App\Models\DoctorScheduleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $exceptions = $user->doctor->exceptions()
            ->with('clinic')
            ->orderBy('exception_date', 'desc')
            ->paginate(10);

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
            'exception_date' => 'required|date|after_or_equal:today',
            'is_available' => 'boolean',
            'start_time' => 'nullable|required_if:is_available,1|date_format:H:i',
            'end_time' => 'nullable|required_if:is_available,1|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:255',
        ]);

        $doctor = $user->doctor;

        $doctor->exceptions()->create([
            'clinic_id' => $user->clinic_id, // Current clinic context
            'exception_date' => $request->exception_date,
            'is_available' => $request->has('is_available') ? $request->is_available : false,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending', // Requires admin approval
        ]);

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

        $exception->delete();

        return back()->with('success', 'Exception request cancelled.');
    }
}
