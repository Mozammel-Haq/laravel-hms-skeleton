<?php

namespace App\Notifications;

use App\Models\Admission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatientAdmittedNotification extends Notification
{
    use Queueable;

    public $admission;

    public function __construct(Admission $admission)
    {
        $this->admission = $admission;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $title = 'New Patient Admitted';
        $message = "Patient {$this->admission->patient->name} has been admitted under your care.";
        $link = route('ipd.show', $this->admission->id);

        if ($notifiable instanceof \App\Models\Patient) {
            $title = 'Admission Confirmed';
            $message = "You have been admitted successfully. Your Admission ID is #{$this->admission->id}.";
            $link = null;
        }

        return [
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'info',
            'admission_id' => $this->admission->id,
        ];
    }
}
