<?php

namespace App\Http\Controllers;

use App\Models\DoctorScheduleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminScheduleExceptionController extends Controller
{
    public function index()
    {
        // Assuming there is a permission 'manage_schedule_exceptions' or similar. 
        // For now using 'view_doctors' as a proxy or create a new gate.
        Gate::authorize('view_doctors'); 

        $exceptions = DoctorScheduleException::with(['doctor.user', 'clinic'])
            ->where('status', 'pending')
            ->orderBy('start_date', 'asc')
            ->paginate(20);

        return view('admin.schedule.exceptions.index', compact('exceptions'));
    }

    public function update(Request $request, DoctorScheduleException $exception)
    {
        Gate::authorize('update', $exception->doctor); // Use doctor update policy or specific one

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $exception->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Schedule exception ' . $request->status . '.');
    }
}
