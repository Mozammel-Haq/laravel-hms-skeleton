<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Notifications\AppointmentRequestStatusUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAppointmentRequestController extends Controller
{
    public function index()
    {
        // Add permission check
        // $this->authorize('view_appointments');

        $requests = AppointmentRequest::with(['appointment.patient', 'appointment.doctor', 'appointment.clinic'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('appointments.requests.index', compact('requests'));
    }

    public function update(Request $request, AppointmentRequest $appointmentRequest)
    {
        // $this->authorize('update_appointments');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $appointmentRequest) {
            $appointmentRequest->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'processed_by' => Auth::id()
            ]);

            if ($request->status === 'approved') {
                $appointment = $appointmentRequest->appointment;

                if ($appointmentRequest->type === 'cancel') {
                    $appointment->update(['status' => 'cancelled']);
                } elseif ($appointmentRequest->type === 'reschedule') {

                    // Calculate new end time based on duration
                    $oldStart = Carbon::parse($appointment->start_time);
                    $oldEnd = Carbon::parse($appointment->end_time);
                    $durationMinutes = $oldStart->diffInMinutes($oldEnd);

                    if ($durationMinutes <= 0) $durationMinutes = 15; // Fallback default

                    $newStart = Carbon::parse($appointmentRequest->desired_time);
                    $newEnd = $newStart->copy()->addMinutes($durationMinutes);

                    $appointment->update([
                        'appointment_date' => $appointmentRequest->desired_date,
                        'start_time' => $newStart->format('H:i:s'),
                        'end_time' => $newEnd->format('H:i:s'),
                        'status' => 'confirmed' // Or pending, but usually rescheduling implies approval of new slot
                    ]);
                }
            }

            // Notification logic
            if ($appointmentRequest->appointment->patient) {
                $appointmentRequest->appointment->patient->notify(new AppointmentRequestStatusUpdatedNotification($appointmentRequest));
            }
        });

        return redirect()->back()->with('success', 'Request processed successfully.');
    }
}
