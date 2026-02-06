<?php

namespace App\Notifications;

use App\Models\Admission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatientDischargedNotification extends Notification
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
        $title = 'Patient Discharged';
        $message = "Patient {$this->admission->patient->name} has been discharged.";
        $link = route('ipd.show', $this->admission->id);

        if ($notifiable instanceof \App\Models\Patient) {
            $title = 'Discharge Complete';
            $message = "You have been discharged. We hope you recover well.";
            $link = null;
        }

        return [
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'success',
            'admission_id' => $this->admission->id
        ];
    }
}
