<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPrescriptionNotification extends Notification
{
    use Queueable;

    public $prescription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $doctorName = $this->prescription->consultation->doctor->user->name ?? 'Unknown Doctor';
        return [
            'title' => 'New Prescription Received',
            'message' => "You have received a new prescription from Dr. {$doctorName}.",
            'link' => '/patient/prescriptions', // Frontend route
            'type' => 'info',
            'prescription_id' => $this->prescription->id
        ];
    }
}
