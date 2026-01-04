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
        return !is_null(self::$clinicId);
    }
}
