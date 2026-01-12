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
    // No clinic resolved at all
    if (is_null(self::$clinicId)) {
        return false;
    }

    // Super Admin must NEVER be tenant-scoped
    if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
        return false;
    }

    return true;
}

}
