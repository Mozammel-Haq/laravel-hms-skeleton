<x-app-layout>
    <div class="container-fluid mx-2 mt-3">
        <div class="row g-4 mb-4">
            <!-- Doctors KPI Card -->
            <div class="col-xl-3 col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-ca-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-ca-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">DOCTORS
                                </h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $stats['doctors']['total'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 21V21.01M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 17V17.01M12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 13V7" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V16M8 12H16" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Total Doctors</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patients KPI Card -->
            <div class="col-xl-3 col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-ca-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-ca-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">PATIENTS
                                </h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $stats['patients']['total'] }}</h2>
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
                                <p class="text-muted kpi-footer">Total Patients</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments KPI Card -->
            <div class="col-xl-3 col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-ca-3" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-ca-3)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    APPOINTMENTS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $stats['appointments']['total'] }}</h2>
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
                                <p class="text-muted kpi-footer">Total Appointments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue KPI Card -->
            <div class="col-xl-3 col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-ca-4" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-ca-4)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">REVENUE
                                    (7 DAYS)</h6>
                                <h2 class="fw-bold kpi-value mb-0">
                                    ৳{{ number_format($stats['revenue']['last_7_days'], 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1V23" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6"
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
                                <p class="text-muted kpi-footer">Last 7 Days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4 mb-4">
            <!-- Latest Appointments -->
            <div class="col-md-8">
                <div class="card p-2">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Latest Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View
                            All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestAppointments as $appointment)
                                    <tr>
                                        <td>
                                            @if ($appointment->patient)
                                                <a href="{{ route('patients.show', $appointment->patient) }}"
                                                    class="text-decoration-none text-body">
                                                    <div class="fw-semibold">{{ $appointment->patient->name }}</div>
                                                    <div class="small text-muted">
                                                        {{ $appointment->patient->patient_code }}
                                                    </div>
                                                </a>
                                            @else
                                                <div class="fw-semibold text-muted">Unknown Patient</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($appointment->doctor)
                                                <a href="{{ route('doctors.show', $appointment->doctor) }}"
                                                    class="text-decoration-none text-body">
                                                    {{ $appointment->doctor->user?->name ?? 'Deleted Doctor' }}
                                                </a>
                                            @else
                                                Deleted Doctor
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                class="text-decoration-none text-body">
                                                <div>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                                </div>
                                                <div class="small text-muted">{{ $appointment->start_time }}</div>
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    'confirmed' => 'primary',
                                                    'arrived' => 'info',
                                                    'pending' => 'warning',
                                                    'noshow' => 'dark',
                                                ];
                                            @endphp

                                            <span
                                                class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No recent appointments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Doctor Availability -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Doctor Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Active</span>
                            <span class="badge bg-success rounded-pill">{{ $doctorAvailability['active'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Inactive</span>
                            <span class="badge bg-warning rounded-pill">{{ $doctorAvailability['inactive'] }}</span>
                        </div>
                        <hr>
                        <h6 class="mb-3">Popular Doctors</h6>
                        <ul class="list-group list-group-flush">
                            @foreach ($popularDoctors as $doctor)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                        class="d-flex align-items-center text-decoration-none text-body">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="{{ $doctor->user?->name ?? 'Doctor' }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $doctor->user?->name ?? 'Deleted Doctor' }}
                                            </div>
                                            <div class="small text-muted">
                                                @php
                                                    $specData = $doctor->specialization;
                                                    $specData = \Illuminate\Support\Arr::wrap($specData);
                                                    $finalSpecs = [];
                                                    foreach ($specData as $item) {
                                                        if (is_string($item)) {
                                                            $decoded = json_decode($item, true);
                                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                                if (is_array($decoded)) {
                                                                    foreach (\Illuminate\Support\Arr::flatten($decoded) as $sub) {
                                                                        $finalSpecs[] = $sub;
                                                                    }
                                                                } else {
                                                                    $finalSpecs[] = $decoded;
                                                                }
                                                            } else {
                                                                $finalSpecs[] = $item;
                                                            }
                                                        } else {
                                                            $finalSpecs[] = $item;
                                                        }
                                                    }
                                                    $pieces = [];
                                                    foreach (\Illuminate\Support\Arr::flatten($finalSpecs) as $s) {
                                                        if (is_string($s)) {
                                                            foreach (explode(',', $s) as $part) {
                                                                $t = trim($part, " \t\n\r\0\x0B\"'[]");
                                                                if ($t !== '') {
                                                                    $pieces[] = $t;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $pieces = array_slice($pieces, 0, 2);
                                                @endphp
                                                {{ empty($pieces) ? '' : implode(', ', $pieces) }}
                                            </div>
                                        </div>
                                    </a>
                                    <span class="badge bg-light text-dark">
                                        {{ $doctor->appointments_count }} appts
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Top Departments -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Top Departments</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($topDepartments as $dept)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">{{ $dept->name }}</span>
                                    <span>{{ $dept->total }} appointments</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $dept->total > 0 ? ($dept->total / $topDepartments->max('total')) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-md-6">
                <div class="card h-100 mb-2">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Patient</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $invoice)
                                    <tr>
                                        <td>#{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->patient->name }}</td>
                                        <td>৳{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No recent transactions</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
