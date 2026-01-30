<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

/**
 * LabTestOrder Model
 *
 * Represents an order for a lab test for a patient.
 * Tracks the status of the order (pending, completed, etc.).
 *
 * @property int $id
 * @property int $clinic_id
 * @property int|null $appointment_id
 * @property int|null $doctor_id
 * @property int $patient_id
 * @property int|null $lab_test_id
 * @property int|null $invoice_id
 * @property \Illuminate\Support\Carbon $order_date
 * @property string $status 'pending', 'completed', 'cancelled'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LabTestResult[] $results
 * @property-read \App\Models\Invoice|null $invoice
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\LabTest|null $test
 * @property-read \App\Models\Doctor|null $doctor
 */
class LabTestOrder extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($order) {
            $order->notifyRole('Lab Technician', 'New Lab Order', "New lab order for {$order->patient->name}.");
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $testName = $this->test ? $this->test->name : 'Unknown Test';
        return ucfirst($action) . " lab order for {$patientName} (Test: {$testName})";
    }

    protected $guarded = ['id'];

    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * Get the results associated with the order.
     *
     * Relationship: One-to-Many.
     * An order can have multiple results (e.g. if multiple parameters are tested).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LabTestResult::class);
    }

    /**
     * Get the invoice associated with the order.
     *
     * Relationship: Belongs to Invoice.
     * The financial record for this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the appointment associated with the order.
     *
     * Relationship: Belongs to Appointment.
     * Optional link if order was made during an appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient associated with the order.
     *
     * Relationship: Belongs to Patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the test type associated with the order.
     *
     * Relationship: Belongs to LabTest.
     * Defines which test was ordered.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }

    /**
     * Get the doctor who ordered the test.
     *
     * Relationship: Belongs to Doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
