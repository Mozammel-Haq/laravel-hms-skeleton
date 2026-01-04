<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class Department extends BaseTenantModel
{
     public function clinic() { return $this->belongsTo(Clinic::class); }
    public function doctors() { return $this->hasMany(Doctor::class, 'primary_department_id'); }
}
