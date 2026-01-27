<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="page-title mb-0">Receptionist Dashboard</h3>
        </div>
        <div class="row g-4 mb-4">
            <!-- Appointments Today KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-recep-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-recep-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    APPOINTMENTS TODAY</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['appointments_today'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8 7V3M16 7V3M7 11H17M5 21H19C20.1046 21 21 20.1046 21 19V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V19C3 20.1046 3.89543 21 5 21Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V12L15 15" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Scheduled Today</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Patients KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-recep-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-recep-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    PATIENTS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['patients_total'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 20C17 18.3431 15.6569 17 14 17H10C8.34315 17 7 18.3431 7 20M21 20C21 18.3431 19.6569 17 18 17M3 20C3 18.3431 4.34315 17 6 17M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13ZM19 13C20.6569 13 22 11.6569 22 10C22 8.34315 20.6569 7 19 7C18.6743 7 18.3619 7.0522 18.0706 7.15011M5 13C3.34315 13 2 11.6569 2 10C2 8.34315 3.34315 7 5 7C5.3257 7 5.63813 7.0522 5.92939 7.15011"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5V19" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M5 12H19" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Registered Patients</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Recent Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View
                            All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $a)
                                        <tr>
                                            <td>{{ $a->patient->name ?? 'Patient' }}</td>
                                            <td>{{ $a->doctor->user->name ?? 'Doctor' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') }}</td>
                                            <td>
                                                @php
                                                    $aStatus = $a->status ?? 'pending';
                                                    $aColor = match ($aStatus) {
                                                        'completed' => 'success',
                                                        'confirmed' => 'primary',
                                                        'arrived' => 'info',
                                                        'pending' => 'warning',
                                                        'cancelled' => 'danger',
                                                        'noshow' => 'dark',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $aColor }}">{{ ucfirst($aStatus) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">New Patients</h5>
                        <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Registered</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $p)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patients.show', $p) }}"
                                                    class="text-decoration-none text-body">
                                                    {{ $p->name }}
                                                </a>
                                            </td>
                                            <td>{{ $p->created_at }}</td>
                                            <td><span class="badge bg-secondary">{{ $p->status ?? 'active' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('appointments.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Patient</label>
                                <select class="form-select select2" name="patient_id"></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Doctor</label>
                                <select class="form-select select2" name="doctor_id"></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="appointment_date">
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
