<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        // Avoid logging ActivityLog itself to prevent recursion
        if ($model instanceof ActivityLog) {
            return;
        }

        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'clinic_id' => $model->clinic_id ?? Auth::user()->clinic_id ?? null,
                'action' => $action,
                'entity_type' => get_class($model),
                'entity_id' => $model->id,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Fail silently or log to file to avoid breaking the main transaction
            logger()->error("Failed to log activity: " . $e->getMessage());
        }
    }
}
