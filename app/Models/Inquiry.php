<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends BaseTenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'user_id',
        'subject',
        'message',
        'status', // pending, responded, closed
        'priority', // low, medium, high
        'source', // phone, email, walk-in, website
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // The staff handling the inquiry
    }

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " inquiry: {$this->subject}";
    }
}
