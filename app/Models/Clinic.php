<?php

namespace App\Models;

// use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Clinic Model
 *
 * Represents a medical facility/branch.
 * Central entity for multi-tenancy (departments, patients, appointments).
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $registration_number
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string|null $state
 * @property string $country
 * @property string|null $postal_code
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $about
 * @property array|null $services
 * @property string|null $logo_path
 * @property string $timezone
 * @property string $currency
 * @property string|null $opening_time
 * @property string|null $closing_time
 * @property string $status 'active', 'inactive', 'suspended'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $departments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Patient[] $patients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ward[] $wards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Doctor[] $doctors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClinicImage[] $images
 */
class Clinic extends Model
{
    use SoftDeletes;
    protected $casts = [
        'created_at' => 'date',
        'services' => 'array',
    ];
    protected $guarded = ['id'];

    /**
     * Get the departments associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get the users associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the patients associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'clinic_patient');
    }

    /**
     * Get the appointments associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the wards associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    /**
     * Get the invoices associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the doctors associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_clinic');
    }

    /**
     * Get the images associated with the clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ClinicImage::class)->orderBy('sort_order');
    }
}
