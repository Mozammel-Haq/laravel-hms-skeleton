<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class BedAssignment extends BaseTenantModel
{
    protected $guarded = ['id'];
    
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
