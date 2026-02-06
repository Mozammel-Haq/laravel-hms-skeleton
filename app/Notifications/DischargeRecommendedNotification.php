<?php

namespace App\Notifications;

use App\Models\Admission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DischargeRecommendedNotification extends Notification
{
    use Queueable;

    public $admission;
    public $recommendingDoctor;

    public function __construct(Admission $admission, $recommendingDoctor)
    {
        $this->admission = $admission;
        $this->recommendingDoctor = $recommendingDoctor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Discharge Recommended',
            'message' => "Dr. {$this->recommendingDoctor->name} recommended discharge for {$this->admission->patient->name}.",
            'link' => route('ipd.discharge', $this->admission->id),
            'type' => 'warning', // Warning to attract attention
            'admission_id' => $this->admission->id
        ];
    }
}
