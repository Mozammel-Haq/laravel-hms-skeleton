<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BaseTenantModel
{
    use SoftDeletes;
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}
