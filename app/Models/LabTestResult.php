<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * LabTestResult Model
 *
 * Represents the result of a specific lab test order.
 * Stores the result value, reference range, and comments.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $lab_test_order_id
 * @property int $lab_test_id
 * @property string|null $result_value
 * @property string|null $unit
 * @property string|null $reference_range
 * @property string|null $remarks
 * @property string|null $pdf_path
 * @property int $reported_by
 * @property \Illuminate\Support\Carbon $reported_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\LabTestOrder $order
 */

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

class LabTestResult extends BaseTenantModel
{
    use LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::updated(function ($result) {
            // If result is entered (and wasn't before, or just generic update), notify Doctor
            if ($result->isDirty('result_value') && $result->result_value) {
                // Find doctor via order
                if ($result->order && $result->order->doctor && $result->order->doctor->user) {
                    $result->order->doctor->user->notify(new \App\Notifications\GeneralNotification(
                        'Lab Result Ready',
                        "Lab result for {$result->order?->patient?->name} is ready.",
                        // route('lab-results.show', $result->id) // Assuming route
                        null
                    ));
                }
            }
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->order && $this->order->patient ? $this->order->patient->name : 'Unknown Patient';
        $testName = $this->test ? $this->test->name : 'Unknown Test';
        return ucfirst($action) . " lab result for {$patientName} (Test: {$testName})";
    }

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    /**
     * Get the order associated with the result.
     *
     * Relationship: Belongs to LabTestOrder.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LabTestOrder::class, 'lab_test_order_id');
    }

    /**
     * Get the lab test associated with the result.
     *
     * Relationship: Belongs to LabTest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
}
