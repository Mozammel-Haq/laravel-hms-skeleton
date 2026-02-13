<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Notifications\AppointmentRequestStatusUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * AdminAppointmentRequestController
 *
 * Manages appointment cancellation and rescheduling requests submitted by patients.
 * Allows admins to approve or reject these requests and updates appointments accordingly.
 */
class AdminAppointmentRequestController extends Controller
{
    /**
     * Display a listing of pending appointment requests.
     *
     * Lists all appointment requests with 'pending' status.
     * Requires 'view_appointments' permission.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        // Add permission check
        $this->authorize('view_appointments');

        $requests = AppointmentRequest::with(['appointment' => function ($query) {
            $query->withTrashed()->with([
                'patient' => function ($patientQuery) {
                    $patientQuery->withTrashed();
                },
                'doctor.user',
                'clinic',
            ]);
        }])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('appointments.requests.index', compact('requests'));
    }

    /**
     * Update the status of a specific appointment request.
     *
     * Handles the approval or rejection of an appointment request (cancellation or rescheduling).
     *
     * Actions:
     * - Updates the request status (approved/rejected).
     * - Logs who processed the request and any admin notes.
     * - If approved:
     *   - For 'cancel' requests: Updates the appointment status to 'cancelled'.
     *   - For 'reschedule' requests: Updates the appointment date and time, recalculating the end time based on duration.
     * - Sends a notification to the patient about the status update.
     *
     * Requires 'update_appointments' permission (currently commented out but recommended).
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, AppointmentRequest $appointmentRequest)
    {
        // $this->authorize('update_appointments');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $appointmentRequest) {
            $appointmentRequest->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'processed_by' => Auth::id(),
            ]);

            if ($request->status === 'approved') {
                $appointment = Appointment::withoutTenant()
                    ->withTrashed()
                    ->whereKey($appointmentRequest->appointment_id)
                    ->where('clinic_id', $appointmentRequest->clinic_id)
                    ->first();

                if (! $appointment) {
                    return;
                }

                if ($appointmentRequest->type === 'cancel') {
                    $appointment->update(['status' => 'cancelled']);
                } elseif ($appointmentRequest->type === 'reschedule') {
                    if ($appointment->trashed()) {
                        $appointment->restore();
                    }

                    // Calculate new end time based on duration
                    $oldStart = Carbon::parse($appointment->start_time);
                    $oldEnd = Carbon::parse($appointment->end_time);
                    $durationMinutes = $oldStart->diffInMinutes($oldEnd);

                    if ($durationMinutes <= 0) {
                        $durationMinutes = 15;
                    } // Fallback default

                    $newStart = $appointmentRequest->desired_time instanceof Carbon
                        ? $appointmentRequest->desired_time
                        : Carbon::parse($appointmentRequest->desired_time);
                    $newEnd = $newStart->copy()->addMinutes($durationMinutes);

                    $appointment->update([
                        'appointment_date' => $appointmentRequest->desired_date,
                        'start_time' => $newStart->format('H:i:s'),
                        'end_time' => $newEnd->format('H:i:s'),
                        'status' => 'pending',
                    ]);
                }
            }

            // Notification logic
            $appointmentForNotify = Appointment::withoutTenant()
                ->withTrashed()
                ->with(['patient' => function ($patientQuery) {
                    $patientQuery->withTrashed()->withoutGlobalScope('clinic_access');
                }])
                ->whereKey($appointmentRequest->appointment_id)
                ->where('clinic_id', $appointmentRequest->clinic_id)
                ->first();

            if ($appointmentForNotify?->patient) {
                $appointmentForNotify->patient->notify(new AppointmentRequestStatusUpdatedNotification($appointmentRequest));
            }
        });

        return redirect()->back()->with('success', 'Request processed successfully.');
    }
}
