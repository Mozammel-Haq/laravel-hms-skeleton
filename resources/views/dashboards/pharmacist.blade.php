<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="row g-4 mb-4">
            <!-- Active Prescriptions KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-warning"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-pharma-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-pharma-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">ACTIVE
                                    PRESCRIPTIONS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['prescriptions_active'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 7H15M9 11H15M9 15H13M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z"
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
                                        <path d="M15 12L12 15L9 12" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">To be processed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Today KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-pharma-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-pharma-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">SALES
                                    TODAY</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($cards['sales_today'], 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.70711 15.2929C4.07714 15.9229 4.52331 17 5.41421 17H17M17 17C15.8954 17 15 17.8954 15 19C15 20.1046 15.8954 21 17 21C18.1046 21 19 20.1046 19 19C19 17.8954 18.1046 17 17 17ZM9 19C9 20.1046 8.10457 21 7 21C5.89543 21 5 20.1046 5 19C5 17.8954 5.89543 17 7 17C8.10457 17 9 17.8954 9 19Z"
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
                                <p class="text-muted kpi-footer">Total Revenue</p>
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
                        <h5 class="mb-0">Recent Prescriptions</h5>
                        <a href="{{ route('clinical.prescriptions.index') }}"
                            class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Issued</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prescriptions as $p)
                                        <tr>
                                            <td>
                                                @if ($p->consultation?->patient)
                                                    <a href="{{ route('patients.show', $p->consultation->patient) }}"
                                                        class="text-decoration-none text-body">
                                                        {{ $p->consultation->patient->name }}
                                                    </a>
                                                @else
                                                    Patient
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($p->issued_at)->format('M d, Y') }}</td>
                                            <td>
                                                @php
                                                    $pStatus = $p->status ?? 'active';
                                                    $pColor = match ($pStatus) {
                                                        'active' => 'success',
                                                        'trashed', 'cancelled' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $pColor }}">{{ ucfirst($pStatus) }}</span>
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
                        <h5 class="mb-0">Recent Sales</h5>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $s)
                                        <tr>
                                            <td>
                                                @if ($s->patient)
                                                    <a href="{{ route('patients.show', $s->patient) }}"
                                                        class="text-decoration-none text-body">
                                                        {{ $s->patient->name }}
                                                    </a>
                                                @else
                                                    Patient
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($s->sale_date ?? $s->created_at)->format('M d, Y') }}
                                            </td>
                                            <td>৳{{ number_format($s->total_amount, 2) }}</td>
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
