<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Support\TenantContext;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy
    }

    public function rules(): array
    {
        $clinicId = TenantContext::getClinicId();

        // Ensure we get the patient ID correctly, handling both Model object and ID string
        $patient = $this->route('patient');
        $patientId = $patient instanceof \App\Models\Patient ? $patient->id : $patient;

        return [
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'blood_group' => ['nullable', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'nid_number' => [
                'nullable',
                'regex:/^[0-9]{10,17}$/',
                'required_without_all:birth_certificate_number,passport_number',
                Rule::unique('patients')->ignore($patientId)->where('clinic_id', $clinicId)
            ],
            'birth_certificate_number' => [
                'nullable',
                'regex:/^[0-9]{10,20}$/',
                'required_without_all:nid_number,passport_number',
                Rule::unique('patients')->ignore($patientId)->where('clinic_id', $clinicId)
            ],
            'passport_number' => [
                'nullable',
                'alpha_num',
                'min:6',
                'max:20',
                'required_without_all:nid_number,birth_certificate_number',
                Rule::unique('patients')->ignore($patientId)->where('clinic_id', $clinicId)
            ],
        ];
    }
}
