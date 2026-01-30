<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * Patient Model
 *
 * Represents a patient in the system.
 * Handles personal details, authentication (portal), and medical records.
 *
 * @property int $id
 * @property int|null $clinic_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $profile_photo
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $phone
 * @property string|null $blood_group
 * @property string|null $nid_number
 * @property string|null $birth_certificate_number
 * @property string|null $passport_number
 * @property string|null $patient_code
 * @property bool $must_change_password
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read int|null $age
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Clinic[] $clinics
 * @property-read \App\Models\Clinic|null $clinic
 */
class Patient extends Model implements AuthenticatableContract
{
    use SoftDeletes, HasApiTokens, AuthenticatableTrait, LogsActivity, Notifiable;

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " patient {$this->name}";
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $fillable = [
        'clinic_id', // Kept for legacy compatibility if needed, but nullable now
        'name',
        'email',
        'password',
        'profile_photo',
        'date_of_birth',
        'phone',
        'blood_group',
        'nid_number',
        'birth_certificate_number',
        'passport_number',
        'patient_code',
        'must_change_password', // Added based on recent migration usage
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the patient's age.
     *
     * @param  mixed  $value
     * @return int|null
     */
    public function getAgeAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($patient) {
            if (empty($patient->patient_code)) {
                $patient->update([
                    'patient_code' => 'P-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT),
                ]);
            }
        });

        // Global Scope to filter patients by the current user's clinic
        static::addGlobalScope('clinic_access', function (Builder $builder) {
            if (auth()->check() && auth()->user()->clinic_id) {
                // Filter patients that belong to the current user's clinic
                // OR patients created by this clinic (legacy clinic_id)
                $builder->where(function ($q) use ($builder) {
                    $q->whereHas('clinics', function ($q2) {
                        $q2->where($q2->qualifyColumn('id'), auth()->user()->clinic_id);
                    })
                        ->orWhere($builder->qualifyColumn('clinic_id'), auth()->user()->clinic_id);
                });
            }
        });
    }

    /**
     * Helper to bypass the tenant scope.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutTenant()
    {
        return static::withoutGlobalScope('clinic_access');
    }

    /**
     * Get the clinics associated with the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clinics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patient');
    }

    /**
     * Get the clinic associated with the patient (Legacy).
     *
     * @deprecated Use clinics() instead.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the appointments for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the allergies for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allergies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientAllergy::class);
    }

    /**
     * Get the medical history for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicalHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientMedicalHistory::class);
    }

    /**
     * Get the surgeries for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function surgeries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientSurgery::class);
    }

    /**
     * Get the immunizations for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function immunizations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientImmunization::class);
    }

    /**
     * Get the consultations for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Get the lab test results for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function labTestResults(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LabTestResult::class);
    }

    /**
     * Get the invoices for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the admissions for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Admission::class);
    }

    /**
     * Get the vitals for the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vitals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PatientVital::class);
    }
}
