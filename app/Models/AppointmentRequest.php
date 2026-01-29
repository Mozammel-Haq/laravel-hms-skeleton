<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentRequest extends BaseTenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'clinic_id',
        'type', // cancel, reschedule
        'reason',
        'desired_date',
        'desired_time',
        'status', // pending, approved, rejected
        'admin_notes',
        'processed_by'
    ];

    protected $casts = [
        'desired_date' => 'date',
        'desired_time' => 'datetime:H:i', // or just string if preferred, but datetime casting is often safer
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
