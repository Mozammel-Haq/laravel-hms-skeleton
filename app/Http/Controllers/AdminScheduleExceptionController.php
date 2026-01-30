<?php

namespace App\Http\Controllers;

use App\Models\DoctorScheduleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * AdminScheduleExceptionController
 *
 * Manages doctor schedule exceptions (leave requests, unavailability) for administrators.
 * Allows viewing and updating the status of exception requests.
 */
class AdminScheduleExceptionController extends Controller
{
    /**
     * Display a listing of schedule exception requests.
     *
     * Supports filtering by:
     * - Status: Filter by request status (pending, approved, rejected).
     * - Search: Search by doctor name or reason.
     * - Date Range: Filter by start date.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Assuming there is a permission 'manage_schedule_exceptions' or similar.
        // For now using 'view_doctors' as a proxy or create a new gate.
        Gate::authorize('view_doctors');

        $query = DoctorScheduleException::with(['doctor.user', 'clinic']);

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->where('status', request('status'));
            }
        } else {
            $query->where('status', 'pending');
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('doctor.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('start_date', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('start_date', '<=', request('to'));
        }

        $exceptions = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.schedule.exceptions.index', compact('exceptions'));
    }

    /**
     * Update the status of a schedule exception request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorScheduleException  $exception
     * @return \Illuminate\Http\RedirectResponse
     */
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
