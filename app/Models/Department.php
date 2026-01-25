<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends BaseTenantModel
{
    use SoftDeletes;
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'primary_department_id');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
