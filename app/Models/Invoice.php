<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BaseTenantModel
{
    use SoftDeletes, LogsActivity;
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
