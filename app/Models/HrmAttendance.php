<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmAttendance extends BaseTenantModel
{
    protected $table = 'hrm_attendances';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'worked_hours',
        'status',
        'is_late',
        'is_early_exit',
        'source',
        'meta',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'worked_hours' => 'float',
        'is_late' => 'boolean',
        'is_early_exit' => 'boolean',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

