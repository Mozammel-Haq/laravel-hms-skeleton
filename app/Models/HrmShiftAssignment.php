<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmShiftAssignment extends BaseTenantModel
{
    protected $table = 'hrm_shift_assignments';

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_primary' => 'boolean',
    ];

    public function shift()
    {
        return $this->belongsTo(HrmShift::class, 'shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

