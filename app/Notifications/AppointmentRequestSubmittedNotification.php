<?php

namespace App\Notifications;

use App\Models\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRequestSubmittedNotification extends Notification
{
    use Queueable;

    public $request;

    public function __construct(AppointmentRequest $request)
    {
        $this->request = $request;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Appointment Request',
            'message' => "Patient requested to {$this->request->type} appointment #{$this->request->appointment_id}",
            'link' => route('appointments.requests.index'), // Link to the request list
            'type' => 'info',
        ];
    }
}
