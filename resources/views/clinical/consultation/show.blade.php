<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="mb-2 fw-bold text-dark">Consultation Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinical.consultations.index') }}" class="text-decoration-none">Consultations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">#{{ $consultation->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto d-flex gap-2">
                <a href="{{ route('clinical.consultations.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
                @can('create', App\Models\Prescription::class)
                    @if ($consultation->status !== 'completed')
                        <a href="{{ route('clinical.prescriptions.create.withConsultation', $consultation->id) }}" class="btn btn-primary">
                            <i class="ti ti-prescription me-1"></i> Create Prescription
                        </a>
                    @endif
                @endcan
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Patient, Doctor, Fee Info -->
            <div class="col-lg-4">
                <!-- Patient Info -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-user me-2 text-primary"></i> Patient
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg rounded-circle me-3 border border-secondary-subtle">
                                @if(optional($consultation->patient)->profile_photo)
                                    <img src="{{ asset($consultation->patient->profile_photo) }}" alt="Patient" class="rounded-circle w-100 h-100 object-fit-cover">
                                @else
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                        {{ strtoupper(substr(optional($consultation->patient)->name ?? 'P', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">
                                    {{ optional($consultation->patient)->full_name ?? (optional($consultation->patient)->name ?? 'Unknown Patient') }}
                                </h6>
                                <small class="text-muted">ID: #{{ optional($consultation->patient)->patient_code ?? optional($consultation->patient)->id }}</small>
                            </div>
                        </div>
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="text-muted d-block">Gender</span>
                                <span class="fw-medium text-dark text-capitalize">{{ optional($consultation->patient)->gender ?? '-' }}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block">Age</span>
                                <span class="fw-medium text-dark">
                                    {{ optional($consultation->patient)->date_of_birth ? \Carbon\Carbon::parse($consultation->patient->date_of_birth)->age . ' Years' : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Info -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-stethoscope me-2 text-primary"></i> Doctor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md rounded-circle me-3 border border-secondary-subtle">
                                @if(optional($consultation->doctor)->profile_photo)
                                    <img src="{{ asset($consultation->doctor->profile_photo) }}" alt="Doctor" class="rounded-circle w-100 h-100 object-fit-cover">
                                @else
                                    <img src="{{ asset('assets/img/doctors/doctor-01.jpg') }}" alt="Doctor" class="rounded-circle w-100 h-100 object-fit-cover">
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ optional($consultation->doctor?->user)->name ?? 'Unknown Doctor' }}</h6>
                                <small class="text-primary">{{ optional($consultation->doctor?->department)->name ?? 'General' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation Fee Info -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-receipt me-2 text-primary"></i> Fee Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted">Visit Type</span>
                                <span class="fw-medium text-dark">
                                    {{ ($feeInfo['type'] ?? 'new') === 'follow_up' ? 'Follow-up' : 'Initial Visit' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted">Discount</span>
                                <span class="fw-medium text-dark">
                                    {{ $feeInfo['is_discounted'] ?? false ? 'Applied' : 'None' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 py-2 border-bottom-0">
                                <span class="fw-bold text-dark">Total Fee</span>
                                <span class="fw-bold text-primary">{{ number_format($feeInfo['fee'] ?? 0, 2) }} BDT</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Prescriptions -->
            <div class="col-lg-8">
                <!-- Clinical Notes -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-clipboard-heart me-2 text-primary"></i> Clinical Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Symptoms</label>
                            @php
                                $symptoms = is_array($consultation->symptoms)
                                    ? $consultation->symptoms
                                    : ($consultation->symptoms
                                        ? [$consultation->symptoms]
                                        : []);
                            @endphp
                            @if (!empty($symptoms))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($symptoms as $symptom)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                            {{ $symptom }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted fst-italic">No symptoms recorded.</p>
                            @endif
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Diagnosis</label>
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    {{ $consultation->diagnosis ?? 'No diagnosis recorded.' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Additional Notes</label>
                                <div class="p-3 bg-light rounded border border-light-subtle">
                                    {{ optional($consultation->prescription)->notes ?? 'No additional notes.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescriptions -->
                <div class="card border border-secondary-subtle shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-pill me-2 text-primary"></i> Prescriptions
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($consultation->prescriptions as $prescription)
                            <div class="border-bottom last-border-0">
                                <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark me-2">Prescription #{{ $prescription->id }}</span>
                                        <span class="text-muted small">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ $prescription->issued_at ? \Carbon\Carbon::parse($prescription->issued_at)->format('d M Y, h:i A') : 'N/A' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('clinical.prescriptions.show', $prescription->id) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-white text-muted small text-uppercase">
                                            <tr>
                                                <th class="ps-4">Medicine</th>
                                                <th>Dosage</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                                <th>Instructions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($prescription->items as $item)
                                                <tr>
                                                    <td class="ps-4 fw-medium text-dark">{{ optional($item->medicine)->name ?? 'Medicine' }}</td>
                                                    <td>{{ $item->dosage ?? '-' }}</td>
                                                    <td>{{ $item->frequency ?? '-' }}</td>
                                                    <td>{{ $item->duration_days ? $item->duration_days . ' Days' : '-' }}</td>
                                                    <td class="text-muted small">{{ $item->instructions ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-3 text-muted">No medicines added to this prescription.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ti ti-prescription fs-1 text-muted opacity-50"></i>
                                </div>
                                <h6 class="text-muted">No prescriptions created yet.</h6>
                                @can('create', App\Models\Prescription::class)
                                    @if ($consultation->status !== 'completed')
                                        <a href="{{ route('clinical.prescriptions.create.withConsultation', $consultation->id) }}" class="btn btn-sm btn-primary mt-2">
                                            Create First Prescription
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
