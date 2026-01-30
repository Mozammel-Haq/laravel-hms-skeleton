<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

trait NotifiesRoles
{
    /**
     * Notify users with a specific role in the same clinic.
     *
     * @param string $roleName
     * @param string $title
     * @param string $message
     * @param string|null $actionUrl
     * @return void
     */
    public function notifyRole($roleName, $title, $message, $actionUrl = null)
    {
        try {
            $clinicId = $this->clinic_id ?? (auth()->check() ? auth()->user()->clinic_id : null);
            
            if (!$clinicId) {
                return;
            }

            $users = User::where('clinic_id', $clinicId)
                ->whereHas('roles', function ($q) use ($roleName) {
                    $q->where('name', $roleName);
                })
                ->get();

            if ($users->isNotEmpty()) {
                Notification::send($users, new GeneralNotification($title, $message, $actionUrl));
            }
        } catch (\Exception $e) {
            logger()->error("Failed to notify role {$roleName}: " . $e->getMessage());
        }
    }
}
