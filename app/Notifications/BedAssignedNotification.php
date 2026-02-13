<?php

namespace App\Notifications;

use App\Models\Admission;
use App\Models\Bed;
use Illuminate\Bus\Queueable;
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
        $title = 'Bed Assigned';
        $message = "Bed {$this->bed->bed_number} (Room: {$this->bed->room->room_number}) has been assigned.";
        $link = route('ipd.show', $this->admission->id);

        if ($notifiable instanceof \App\Models\Patient) {
            $message = "You have been assigned to Bed {$this->bed->bed_number} in Room {$this->bed->room->room_number}.";
            $link = null;
        }

        return [
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'info',
        ];
    }
}
