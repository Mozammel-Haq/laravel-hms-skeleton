<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsActivity;

/**
 * InpatientRound Model
 *
 * Represents a doctor's round visit to an admitted patient.
 * Tracks the doctor, admission, and associated vitals.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $admission_id
 * @property int $doctor_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $round_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Admission $admission
 * @property-read \App\Models\Doctor $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientVital[] $vitals
 */
class InpatientRound extends BaseTenantModel
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivityDescription($action)
    {
        $doctorName = $this->doctor ? $this->doctor->user->name : 'Unknown Doctor';
        return ucfirst($action) . " round check for Admission #{$this->admission_id} by Dr. {$doctorName}";
    }

    /**
     * Get the admission record associated with this round.
     *
     * Relationship: Belongs to Admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the doctor who performed the round.
     *
     * Relationship: Belongs to Doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the vitals recorded during this round.
     *
     * Relationship: Has Many.
     * Vitals taken specifically during this round.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vitals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientVital::class, 'inpatient_round_id');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
