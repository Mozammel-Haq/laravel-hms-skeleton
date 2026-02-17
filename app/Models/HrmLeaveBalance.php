<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmLeaveBalance extends BaseTenantModel
{
    protected $table = 'hrm_leave_balances';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'leave_type',
        'year',
        'opening_balance',
        'accrued',
        'used',
        'closing_balance',
        'status',
    ];

    protected $casts = [
        'opening_balance' => 'float',
        'accrued' => 'float',
        'used' => 'float',
        'closing_balance' => 'float',
        'year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

