<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->appointment->status);
        $type = match($this->appointment->status) {
            'confirmed' => 'success',
            'completed' => 'success',
            'cancelled' => 'error',
            default => 'info',
        };

        return [
            'title' => "Appointment {$status}",
            'message' => "Your appointment on {$this->appointment->appointment_date->format('M d, Y')} is now {$this->appointment->status}.",
            'link' => '/patient/appointments',
            'type' => $type,
            'appointment_id' => $this->appointment->id
        ];
    }
}
