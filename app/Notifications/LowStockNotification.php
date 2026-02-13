<?php

namespace App\Notifications;

use App\Models\Medicine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $medicine;

    protected $currentStock;

    public function __construct(Medicine $medicine, int $currentStock)
    {
        $this->medicine = $medicine;
        $this->currentStock = $currentStock;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Low Medicine Stock',
            'message' => "Stock for {$this->medicine->name} is low ({$this->currentStock} remaining).",
            'link' => route('pharmacy.medicines.show', $this->medicine->id),
            'type' => 'warning',
        ];
    }
}
