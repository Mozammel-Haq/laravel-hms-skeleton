<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h3 class="page-title mb-0">IPD Dashboard</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-outline-primary">Bed Assignments</a>
                <a href="{{ route('ipd.create') }}" class="btn btn-primary">Admit Patient</a>
            </div>
        </div>
        <div class="row mb-5 g-4">
            <!-- Admissions Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    ADMISSIONS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $admissionsCount }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" />
                                    <path d="M5 20C5 16.134 8.13401 13 12 13C15.866 13 19 16.134 19 20"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round" />
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
                                <p class="text-muted kpi-footer">Total Admissions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beds Available Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-dots-2" x="0" y="0" width="30" height="30"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--info-color)"
                                        fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-dots-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                        style="background: radial-gradient(circle at bottom left, var(--info-color) 0%, transparent 70%); opacity: 0.1;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">BEDS
                                    AVAILABLE</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $bedsAvailable }}</h2>
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
                                <p class="text-muted kpi-footer">Vacant Beds</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beds Occupied Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-lines-3" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <line x1="0" y1="40" x2="40" y2="0"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                    <line x1="0" y1="0" x2="40" y2="40"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-lines-3)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                        style="background: radial-gradient(circle at center, var(--success-color) 0%, transparent 70%); opacity: 0.08;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">BEDS
                                    OCCUPIED</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $bedsOccupied }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
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
                                        <path
                                            d="M8 7H16C18.7614 7 21 9.23858 21 12C21 14.7614 18.7614 17 16 17H8C5.23858 17 3 14.7614 3 12C3 9.23858 5.23858 7 8 7Z"
                                            stroke="var(--success-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Occupied</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wards / Rooms Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-diagonal-4" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <path d="M0 20L20 0" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M-10 10L10 -10" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M10 30L30 10" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-diagonal-4)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 start-0 w-25 h-25 decorative-shape">
                        <svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0,0 L100,0 L0,100 Z" fill="var(--warning-color)" fill-opacity="0.1" />
                        </svg>
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">WARDS /
                                    ROOMS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $totalWards }} / {{ $totalRooms }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 12H22M6 16H10M16 16H18M5 20H19C20.1046 20 21 19.1046 21 18V6C21 4.89543 20.1046 4 19 4H5C3.89543 4 3 4.89543 3 6V18C3 19.1046 3.89543 20 5 20Z"
                                        stroke="var(--warning-color)" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2V6" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Total Wards / Rooms</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('ipd.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Patient, Doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="admitted"
                                    {{ request('status', 'admitted') == 'admitted' ? 'selected' : '' }}>Admitted
                                </option>
                                <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>
                                    Discharged</option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses
                                </option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('ipd.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admitted On</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>
                                        @if ($admission->patient)
                                            <a href="{{ route('patients.show', $admission->patient) }}"
                                                class="text-decoration-none text-body">
                                                {{ $admission->patient->name ?? ($admission->patient->full_name ?? 'Patient') }}
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td>
                                        @if ($admission->doctor)
                                            <a href="{{ route('doctors.show', $admission->doctor) }}"
                                                class="d-flex align-items-center text-decoration-none text-body">
                                                <div class="avatar avatar-sm me-2">
                                                    <img src="{{ $admission->doctor->profile_photo ? asset($admission->doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                        alt="{{ $admission->doctor->user?->name }}"
                                                        class="rounded-circle w-100 h-100 object-fit-cover">
                                                </div>
                                                <div>
                                                    {{ $admission->doctor->user?->name ?? 'Doctor' }}
                                                </div>
                                            </a>
                                        @else
                                            Doctor
                                        @endif
                                    </td>
                                    <td>{{ $admission->created_at }}</td>
                                    <td>
                                        @php
                                            $admStatus = $admission->status;
                                            $admColor = match ($admStatus) {
                                                'discharged' => 'success',
                                                'admitted' => 'warning',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $admColor }}">{{ ucfirst($admStatus) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon dropdown-toggle hide-arrow"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if ($admission->trashed())
                                                    <li>
                                                        <form action="{{ route('ipd.restore', $admission->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Are you sure you want to restore this admission?')">
                                                                <i class="ti ti-refresh me-1"></i> Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('ipd.show', $admission->id) }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('ipd.assign-bed', $admission->id) }}">
                                                            <i class="ti ti-bed me-1"></i> Assign Bed
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('ipd.destroy', $admission) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this admission?')">
                                                                <i class="ti ti-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No current admissions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $admissions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
