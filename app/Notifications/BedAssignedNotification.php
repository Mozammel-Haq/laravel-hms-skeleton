<?php

namespace App\Notifications;

use App\Models\Admission;
use App\Models\Bed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BedAssignedNotification extends Notification
{
    use Queueable;

    public $admission;
    public $bed;

    /**
     * Create a new notification instance.
     */
    public function __construct(Admission $admission, Bed $bed)
    {
        $this->admission = $admission;
        $this->bed = $bed;
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
        return [
            'title' => 'Bed Assigned',
            'message' => "Bed {$this->bed->bed_number} (Room: {$this->bed->room->room_number}) has been assigned.",
            'link' => route('ipd.show', $this->admission->id),
            'type' => 'info',
        ];
    }
}
