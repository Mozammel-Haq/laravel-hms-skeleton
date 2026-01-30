<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;
use App\Models\DoctorAward;
use App\Models\DoctorCertification;
use App\Models\DoctorEducation;

/**
 * Doctor Model
 *
 * Represents a doctor profile linked to a User.
 * Manages specialization, schedules, and clinic associations.
 *
 * @property int $id
 * @property int $user_id
 * @property int $primary_department_id
 * @property string|null $registration_number
 * @property string|null $license_number
 * @property array $specialization
 * @property int $experience_years
 * @property string|null $gender
 * @property string|null $blood_group
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $consultation_fee
 * @property string|null $follow_up_fee
 * @property string|null $biography
 * @property string|null $profile_photo
 * @property bool $is_featured
 * @property string $status 'active', 'inactive'
 * @property string|null $consultation_room_number
 * @property string|null $consultation_floor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Department $department
 * @property-read \App\Models\Department $primaryDepartment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorSchedule[] $schedules
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorScheduleException[] $exceptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Clinic[] $clinics
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Consultation[] $consultations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Prescription[] $prescriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorEducation[] $educations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorAward[] $awards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorCertification[] $certifications
 */
class Doctor extends Model
{
    use SoftDeletes, LogsActivity;

    public function getActivityDescription($action)
    {
        $userName = $this->user ? $this->user->name : 'Unknown User';
        return ucfirst($action) . " doctor profile for {$userName}";
    }

    protected $guarded = ['id'];

    /**
     * Get the user associated with the doctor profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withoutGlobalScope('clinic');
    }

    /**
     * Get the primary department of the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'primary_department_id')->withoutGlobalScope('clinic');
    }

    /**
     * Get the primary department of the doctor (Alias).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function primaryDepartment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'primary_department_id')->withoutGlobalScope('clinic');
    }

    /**
     * Get the schedules for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the schedule exceptions for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exceptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorScheduleException::class);
    }

    /**
     * Get the clinics associated with the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clinics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'doctor_clinic');
    }

    /**
     * Get the appointments for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the consultations for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Get the prescriptions issued by the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function prescriptions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Prescription::class, Consultation::class, 'doctor_id', 'consultation_id');
    }

    /**
     * Get the education records for the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorEducation::class)->orderByDesc('end_year')->orderByDesc('start_year');
    }

    /**
     * Get the awards received by the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function awards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorAward::class)->orderByDesc('year');
    }

    /**
     * Get the certifications held by the doctor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorCertification::class)->orderByDesc('issued_date');
    }
    protected $casts = [
        'created_at' => 'date',
        'specialization' => 'array',
    ];
}
