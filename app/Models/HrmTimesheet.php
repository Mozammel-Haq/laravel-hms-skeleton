<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmTimesheet extends BaseTenantModel
{
    protected $table = 'hrm_timesheets';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'date',
        'hours',
        'project',
        'task',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

