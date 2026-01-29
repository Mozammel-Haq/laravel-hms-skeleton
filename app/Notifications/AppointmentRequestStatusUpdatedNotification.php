<?php

namespace App\Notifications;

use App\Models\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRequestStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $request;

    public function __construct(AppointmentRequest $request)
    {
        $this->request = $request;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Frontend patient portal fetches notifications from DB
    }

    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->request->status);
        $type = ucfirst($this->request->type);
        return [
            'title' => "Appointment Request {$status}",
            'message' => "Your request to {$type} appointment #{$this->request->appointment_id} has been {$this->request->status}.",
            'link' => '/portal/appointments', // Frontend link
            'type' => $this->request->status === 'approved' ? 'success' : 'danger',
        ];
    }
}
