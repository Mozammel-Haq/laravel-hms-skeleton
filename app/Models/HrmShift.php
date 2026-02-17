<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmShift extends BaseTenantModel
{
    protected $table = 'hrm_shifts';

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assignments()
    {
        return $this->hasMany(HrmShiftAssignment::class, 'shift_id');
    }
}

