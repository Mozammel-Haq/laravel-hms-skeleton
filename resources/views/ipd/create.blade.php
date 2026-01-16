<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Admit Patient</h4>
                <p class="text-muted mb-0">Create a new inpatient admission</p>
            </div>
            <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to IPD
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('ipd.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="patient_id" :value="__('Select Patient')" />
                            <select id="patient_id" name="patient_id" class="form-select mt-1 block w-full" required>
                                <option value="">Select a patient...</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ $patient->patient_code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            @if ($patients->isEmpty())
                                <div class="form-text text-warning">
                                    No patients found. <a href="{{ route('patients.create') }}">Register a patient
                                        first.</a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="doctor_id" :value="__('Attending Doctor')" />
                            <select id="doctor_id" name="doctor_id" class="form-select mt-1 block w-full" required>
                                <option value="">Select a doctor...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->user?->name ?? 'Unknown' }}
                                        ({{ $doctor->department?->name ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="admission_date" :value="__('Admission Date')" />
                            <x-text-input id="admission_date" class="block mt-1 w-full form-control"
                                type="datetime-local" name="admission_date" :value="old('admission_date', now()->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                        </div>

                        <div class="col-md-12">
                            <x-input-label for="admission_reason" :value="__('Admission Notes / Reason')" />
                            <textarea id="admission_reason" name="admission_reason" class="form-control mt-1 block w-full" rows="4"
                                placeholder="Enter reason for admission and initial notes...">{{ old('admission_reason') }}</textarea>
                            <x-input-error :messages="$errors->get('admission_reason')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" {{ $patients->isEmpty() ? 'disabled' : '' }}>
                            <i class="ti ti-check me-1"></i> Admit Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
