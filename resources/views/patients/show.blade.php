<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Patient Profile</h4>
            <p class="text-muted">View patient details and history</p>
        </div>
        <div class="action-btn">
            <a href="{{ route('patients.index') }}" class="btn btn-light me-2">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
            @can('update', $patient)
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <!-- Patient Info Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3 mx-auto">
                        <span class="avatar-title rounded-circle bg-primary text-white fs-1">
                            {{ substr($patient->name, 0, 1) }}
                        </span>
                    </div>
                    <h5 class="card-title mb-1">{{ $patient->name }}</h5>
                    <p class="text-muted mb-3">{{ $patient->patient_code }}</p>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <a href="tel:{{ $patient->phone }}" class="btn btn-sm btn-light">
                            <i class="ti ti-phone me-1"></i> Call
                        </a>
                        @if ($patient->email)
                            <a href="mailto:{{ $patient->email }}" class="btn btn-sm btn-light">
                                <i class="ti ti-mail me-1"></i> Email
                            </a>
                        @endif
                    </div>

                    <div class="text-start">
                        <h6 class="text-muted text-uppercase fs-xs fw-bold mb-3">Details</h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Gender</span>
                            <span class="text-capitalize">{{ $patient->gender }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Age</span>
                            <span>{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} Years</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Birth Date</span>
                            <span>{{ $patient->date_of_birth }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Blood Group</span>
                            <span class="text-danger fw-bold">{{ $patient->blood_group ?? 'N/A' }}</span>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">Address</label>
                            <div>{{ $patient->address }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">Emergency Contact</label>
                            <div class="fw-bold">{{ $patient->emergency_contact_name ?? 'N/A' }}</div>
                            <div class="small">{{ $patient->emergency_contact_phone ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#appointments"
                                role="tab">Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#admissions" role="tab">Admissions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#medical" role="tab">Medical History</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Appointments Tab -->
                        <div class="tab-pane active" id="appointments" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Doctor</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->appointments as $appointment)
                                            <tr>
                                                <td>{{ $appointment->appointment_date }}</td>
                                                <td>{{ $appointment->doctor->user->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}-subtle text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('appointments.show', $appointment) }}"
                                                        class="btn btn-sm btn-light btn-icon">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No appointments
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Admissions Tab -->
                        <div class="tab-pane" id="admissions" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Admission Date</th>
                                            <th>Discharge Date</th>
                                            <th>Doctor</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->admissions ?? [] as $admission)
                                            <tr>
                                                <td>{{ $admission->admission_date }}</td>
                                                <td>{{ $admission->discharge_date ? $admission->discharge_date : '-' }}
                                                </td>
                                                <td>{{ $admission->doctor->user->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary">{{ ucfirst($admission->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No admissions
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Medical History Tab -->
                        <div class="tab-pane" id="medical" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i> Medical history module integration pending.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
