<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="page-title mb-0">Doctor Dashboard</h3>
        </div>
        <div class="card p-3">
            <div class="row g-4 mb-2">
                <!-- Appointments Today -->
                <div class="col-md-4">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                        data-bs-theme="light,dark">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-circle-doc" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                            fill-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-circle-doc)" />
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
                                    <p class="text-muted kpi-footer">Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescriptions This Month -->
                <div class="col-md-4">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary kpi-info"
                        data-bs-theme="light,dark">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-dots-doc" x="0" y="0" width="30" height="30"
                                        patternUnits="userSpaceOnUse">
                                        <rect x="0" y="0" width="2" height="2" fill="var(--info-color)"
                                            fill-opacity="0.3" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-dots-doc)" />
                            </svg>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                            style="background: radial-gradient(circle at bottom left, var(--info-color) 0%, transparent 70%); opacity: 0.1;">
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                        PRESCRIPTIONS</h6>
                                    <h2 class="fw-bold kpi-value mb-0">{{ $cards['prescriptions_month'] }}</h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21M19 21L21 21M19 21H14M5 21L3 21M5 21H10M10 14H14M10 18H14"
                                            stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="border-top pt-3 mt-3 kpi-divider">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="var(--info-color)" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <p class="text-muted kpi-footer">This Month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Lab Orders -->
                <div class="col-md-4">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                        data-bs-theme="light,dark">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-lines-doc" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <line x1="0" y1="40" x2="40" y2="0"
                                            stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                        <line x1="0" y1="0" x2="40" y2="40"
                                            stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-lines-doc)" />
                            </svg>
                        </div>
                        <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                            style="background: radial-gradient(circle at center, var(--success-color) 0%, transparent 70%); opacity: 0.08;">
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                        PENDING LAB ORDERS</h6>
                                    <h2 class="fw-bold kpi-value mb-0">{{ $cards['lab_orders_pending'] }}</h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 3H15M10 9H14M21 21L3 21L7 3H17L21 21Z"
                                            stroke="var(--success-color)" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="border-top pt-3 mt-3 kpi-divider">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19" stroke="var(--success-color)" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <p class="text-muted kpi-footer">Lab</p>
                                </div>
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
                        <h5 class="mb-0">Upcoming Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View
                            All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $a)
                                        <tr>
                                            <td>
                                                @if ($a->patient)
                                                    <a href="{{ route('patients.show', $a->patient) }}"
                                                        class="text-decoration-none text-body">
                                                        {{ $a->patient->name }}
                                                    </a>
                                                @else
                                                    Patient
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger',
                                                        'confirmed' => 'primary',
                                                        'arrived' => 'primary',
                                                        'pending' => 'warning',
                                                    ];
                                                    $status = $a->status ?? 'pending';
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$status] ?? 'primary' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('appointments.show', $a) }}">
                                                                <i class="ti ti-eye me-1"></i> Open
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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
                        <h5 class="mb-0">Recent Prescriptions</h5>
                        <a href="{{ route('clinical.prescriptions.index') }}"
                            class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Issued</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prescriptions as $p)
                                        <tr>
                                            <td>{{ $p->consultation->patient->name ?? 'Patient' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->issued_at)->format('M d, Y') }}</td>
                                            <td>
                                                @php
                                                    $pStatus = $p->status ?? 'active';
                                                    $pColor = match ($pStatus) {
                                                        'active' => 'success',
                                                        'trashed', 'cancelled' => 'danger',
                                                        default => 'primary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $pColor }}">{{ ucfirst($pStatus) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('clinical.prescriptions.show', $p) }}">
                                                                <i class="ti ti-eye me-1"></i> Open
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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
    </div>
</x-app-layout>
