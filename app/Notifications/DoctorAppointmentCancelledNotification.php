<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DoctorAppointmentCancelledNotification extends Notification
{
    use Queueable;

    public $appointment;

    public $cancelledBy;

    public function __construct(Appointment $appointment, $cancelledBy = null)
    {
        $this->appointment = $appointment;
        $this->cancelledBy = $cancelledBy; // Could be 'Patient', 'Staff', or a User name
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $patientName = $this->appointment->patient->name ?? 'Patient';
        $date = $this->appointment->appointment_date->format('M d, Y');
        $time = $this->appointment->start_time;

        return [
            'title' => 'Appointment Cancelled',
            'message' => "Appointment with {$patientName} on {$date} at {$time} has been cancelled.",
            'link' => route('appointments.show', $this->appointment->id),
            'type' => 'error',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
