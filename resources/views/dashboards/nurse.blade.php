<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="row g-4 mb-4">
            <!-- Active Admissions KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-nurse-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-nurse-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">ACTIVE
                                    ADMISSIONS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['admissions_active'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4 19V15C4 13.8954 4.89543 13 6 13H18C19.1046 13 20 13.8954 20 15V19M2 19H22M6 13V10M18 13V10M8 8C8 9.10457 7.10457 10 6 10C4.89543 10 4 9.10457 4 8C4 6.89543 4.89543 6 6 6C7.10457 6 8 6.89543 8 8Z"
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
                                <p class="text-muted kpi-footer">Currently Admitted</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beds Available KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-nurse-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-nurse-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">BEDS
                                    AVAILABLE</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['beds_available'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3 21H21M5 21V7C5 5.89543 5.89543 5 7 5H17C18.1046 5 19 5.89543 19 7V21M9 9H10M9 13H10M9 17H10M14 9H15M14 13H15M14 17H15"
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
                                        <path d="M9 12L11 14L15 10" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Available for Admission</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Admitted Patients</h5>
                <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-primary">Manage IPD</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissions as $ad)
                                <tr>
                                    <td>
                                        @if ($ad->patient)
                                            <a href="{{ route('patients.show', $ad->patient) }}"
                                                class="text-decoration-none text-body">
                                                {{ $ad->patient->name ?? 'Patient' }}
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ad->doctor)
                                            <a href="{{ route('doctors.show', $ad->doctor) }}"
                                                class="text-decoration-none text-body">
                                                {{ $ad->doctor->user->name ?? 'Doctor' }}
                                            </a>
                                        @else
                                            Doctor
                                        @endif
                                    </td>
                                    <td>{{ $ad->created_at?->format('d M, H:i') }}</td>
                                    <td>
                                        @php
                                            $adStatus = $ad->status;
                                            $adColor = match ($adStatus) {
                                                'active', 'admitted' => 'success',
                                                'discharged' => 'warning',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $adColor }}">{{ ucfirst($adStatus) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('ipd.show', $ad) }}"
                                            class="btn btn-sm btn-primary">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
