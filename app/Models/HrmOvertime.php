<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmOvertime extends BaseTenantModel
{
    protected $table = 'hrm_overtimes';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'date',
        'hours',
        'multiplier',
        'reason',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'float',
        'multiplier' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

