<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\LabTestOrder;

class NewLabOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(LabTestOrder $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Lab Order',
            'message' => "New lab order for {$this->order->patient->name} (Test: {$this->order->test->name})",
            'link' => route('lab.show', $this->order->id),
            'type' => 'info',
        ];
    }
}
