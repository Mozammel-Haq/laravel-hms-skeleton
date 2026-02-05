<x-app-layout>
    <style>
        .doctor-card-img {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .hover-border-primary {
            transition: border-color 0.2s ease-in-out;
        }
        .hover-border-primary:hover {
            border-color: var(--bs-primary) !important;
        }
        .doctor-grid-card {
            max-width: 250px;
        }
    </style>
    <div class="container-fluid mx-2 mt-2">

        <div class="card border-0 shadow-sm rounded-bottom rounded-0">
            <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Find a Doctor</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Find a Doctor</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
                <!-- Filter Form -->
                <form method="GET" action="{{ route('appointments.booking.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted mb-1">Clinic</label>
                            <select class="form-select form-select-sm" name="clinic_id">
                                <option value="">All Clinics</option>
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}"
                                        {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted mb-1">Department</label>
                            <select class="form-select form-select-sm" name="department_id">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All
                                    Status</option>
                                <option value="active"
                                    {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive"
                                    {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Search</button>
                                <a href="{{ route('appointments.booking.index') }}"
                                    class="btn btn-sm btn-light border w-100">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>

                <!-- Doctors Grid -->
                <div class="row g-3">
                    @forelse($doctors as $doctor)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="card h-100 border rounded-4 overflow-hidden position-relative group hover-border-primary doctor-grid-card mx-auto">
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                    <span class="badge bg-white text-dark border rounded-pill px-2 py-1 fs-11 fw-medium">
                                        <i class="fas fa-circle text-{{ $doctor->status === 'active' ? 'success' : 'secondary' }} me-1" style="font-size: 6px; vertical-align: middle;"></i>
                                        {{ ucfirst($doctor->status ?? 'Active') }}
                                    </span>
                                </div>

                                <div class="card-body p-0">
                                    <!-- Header Background -->
                                    <div class="bg-primary-subtle opacity-50" style="height: 70px;"></div>

                                    <div class="text-center px-3 position-relative" style="margin-top: -35px;">
                                        <!-- Avatar -->
                                        <a href="{{ route('appointments.booking.show', $doctor) }}" class="d-inline-block position-relative">
                                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                 class="rounded-circle doctor-card-img bg-white"
                                                 alt="{{ $doctor->user?->name }}">
                                        </a>

                                        <!-- Info -->
                                        <div class="mt-2">
                                            <h6 class="fw-bold text-dark mb-0 text-truncate">
                                                <a href="{{ route('appointments.booking.show', $doctor) }}" class="text-dark text-decoration-none stretched-link">
                                                    {{ $doctor->user?->name ?? 'Unknown Doctor' }}
                                                </a>
                                            </h6>
                                            <div class="small text-primary fw-medium mb-2">{{ $doctor->department->name ?? 'General' }}</div>

                                            <!-- Stats/Meta -->
                                            <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
                                                 @if ($doctor->consultation_room_number)
                                                    <span class="badge bg-light text-muted border fw-normal rounded-pill px-2" title="Room Number">
                                                        <i class="ti ti-door small me-1"></i>{{ $doctor->consultation_room_number }}
                                                    </span>
                                                @endif
                                                 <span class="badge bg-light text-muted border fw-normal rounded-pill px-2" title="Consultation Fee">
                                                    <i class="ti ti-wallet small me-1"></i>${{ number_format($doctor->consultation_fee ?? 0) }}
                                                </span>
                                            </div>

                                            <!-- Clinics -->
                                            @if($doctor->clinics->isNotEmpty())
                                                <div class="d-flex flex-wrap justify-content-center gap-1 mb-1 px-2">
                                                    @foreach($doctor->clinics as $clinic)
                                                        <div class="d-inline-flex align-items-center gap-1 bg-light rounded-pill border px-2 py-1" style="font-size: 0.7rem;">
                                                            <i class="ti ti-map-pin text-secondary" style="font-size: 0.65rem;"></i>
                                                            <span class="text-secondary fw-medium text-truncate" style="max-width: 100px;">{{ $clinic->name }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="card-footer bg-transparent border-0 p-3 pt-0">
                                    <a href="{{ route('appointments.booking.show', $doctor) }}" class="btn btn-primary w-100 rounded-pill btn-sm fw-medium position-relative" style="z-index: 20;">
                                        Book Appointment
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-search text-muted opacity-25" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted fw-normal">No doctors found matching your criteria.</h5>
                            <p class="text-muted small">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('appointments.booking.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Clear Filters</a>
                        </div>
                    @endforelse
                </div>


                        <!-- Pagination -->
                        <div class="row">
                            <div class="col-sm-12">
                                {{ $doctors->withQueryString()->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
