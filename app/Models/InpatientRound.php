<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;

class InpatientRound extends BaseTenantModel
{
    protected $guarded = ['id'];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function vitals()
    {
        return $this->hasMany(PatientVital::class, 'inpatient_round_id');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
