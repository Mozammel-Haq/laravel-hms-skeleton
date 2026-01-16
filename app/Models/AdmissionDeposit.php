<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class AdmissionDeposit extends BaseTenantModel
{
    protected $guarded = ['id'];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}

