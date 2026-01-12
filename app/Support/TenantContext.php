<?php

namespace App\Support;

class TenantContext
{
    protected static ?int $clinicId = null;

    public static function setClinicId(?int $clinicId): void
    {
        self::$clinicId = $clinicId;
    }

    public static function getClinicId(): ?int
    {
        return self::$clinicId;
    }

    public static function hasClinic(): bool
    {
        // Tenant scoping applies whenever a clinic id is explicitly set
        // including Super Admin when switching to a specific clinic context
        return !is_null(self::$clinicId);
    }

}
