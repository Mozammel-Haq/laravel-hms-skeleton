<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Consultation Model
 *
 * Represents a medical consultation record.
 * Stores diagnosis, symptoms, and doctor notes.
 *
 * @property int $id
 * @property int $visit_id
 * @property string|null $doctor_notes
 * @property string|null $diagnosis
 * @property bool $follow_up_required
 * @property \Illuminate\Support\Carbon|null $follow_up_date
 * @property array|null $symptoms
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Visit $visit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Prescription[] $prescriptions
 * @property-read \App\Models\Prescription|null $prescription
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\InvoiceItem|null $invoiceItem
 */
class Consultation extends BaseTenantModel
{
    use SoftDeletes, LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " consultation for {$patientName}";
    }

    public $timestamps = true;

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'patient_id',
        'doctor_notes',
        'diagnosis',
        'follow_up_required',
        'follow_up_date',
        'symptoms',
    ];

    protected $casts = [
        'symptoms' => 'array',
    ];

    /**
     * Get the visit associated with the consultation.
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the prescriptions associated with the consultation.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the latest prescription associated with the consultation.
     */
    public function prescription()
    {
        return $this->hasOne(Prescription::class)->latestOfMany();
    }

    /**
     * Get the doctor who performed the consultation.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient associated with the consultation.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the invoice item associated with the consultation.
     */
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class, 'reference_id')->where('item_type', $this->getTable());
    }
}
