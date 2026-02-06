<?php

namespace App\Notifications;

use App\Models\InpatientRound;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DoctorRoundCreatedNotification extends Notification
{
    use Queueable;

    public $round;

    public function __construct(InpatientRound $round)
    {
        $this->round = $round;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $doctorName = $this->round->doctor->user->name ?? 'Doctor';
        $patientName = $this->round->admission->patient->name ?? 'Patient';

        return [
            'title' => 'Doctor Round Completed',
            'message' => "Dr. {$doctorName} completed a round for {$patientName}.",
            'link' => route('ipd.show', $this->round->admission_id),
            'type' => 'info',
            'round_id' => $this->round->id
        ];
    }
}
