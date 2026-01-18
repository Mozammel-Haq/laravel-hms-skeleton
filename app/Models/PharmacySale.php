<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\SoftDeletes;

class PharmacySale extends BaseTenantModel
{
    use SoftDeletes;
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function items()
    {
        return $this->hasMany(PharmacySaleItem::class);
    }
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
