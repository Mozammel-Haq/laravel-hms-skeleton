<x-app-layout>
    <div class="content">


        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table show-entire">

                    <div class="card-body">
                        <div class="page-table-header mb-2">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="doctor-table-blk">
                                        <h3>Find a Doctor</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('appointments.booking.index') }}" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search doctor..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="select form-control" name="clinic_id">
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
                                    <select class="select form-control" name="department_id">
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
                                    <select class="select form-control" name="status">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All
                                            Status</option>
                                        <option value="active"
                                            {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                    <a href="{{ route('appointments.booking.index') }}"
                                        class="btn btn-light w-100">Reset</a>
                                </div>
                            </div>
                        </form>

                        <!-- Doctors Grid -->
                        <div class="row g-3">
                            @forelse($doctors as $doctor)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="card shadow-sm border border-light doctor-card">

                                        <!-- Card Body -->
                                        <div class="card-body text-center p-3">

                                            <!-- Avatar -->
                                            <a href="{{ route('appointments.booking.show', $doctor) }}"
                                                class="d-inline-block mb-2">
                                                <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                    class="rounded-circle border"
                                                    style="width:90px;height:90px;object-fit:cover;" alt="Doctor">
                                            </a>

                                            <!-- Dropdown -->
                                            <div class="dropdown position-absolute top-0 end-0 m-3">
                                                <button class="btn btn-sm btn-light btn-icon" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('doctors.show', $doctor) }}">
                                                            <i class="ti ti-eye me-1"></i> View Details
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Name -->
                                            <h6 class="fw-semibold mb-0">
                                                <a href="{{ route('appointments.booking.show', $doctor) }}"
                                                    class="text-dark text-decoration-none">
                                                    {{ $doctor->user?->name ?? 'Inactive Doctor' }}
                                                </a>
                                            </h6>

                                            <!-- Department -->
                                            <small class="text-muted d-block mb-2">
                                                {{ $doctor->department->name ?? 'General' }}
                                            </small>

                                            <!-- Clinics -->
                                            <div class="fs-13 text-muted mb-2">
                                                <i class="fa fa-map-marker me-1"></i>
                                                {{ $doctor->clinics->pluck('name')->join(', ') }}
                                            </div>

                                        </div>

                                        <!-- Footer -->
                                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 text-center">
                                            <a href="{{ route('appointments.booking.show', $doctor) }}"
                                                class="btn btn-sm btn-outline-primary px-3">
                                                Book Appointment
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted mb-0">No doctors found matching your criteria.</p>
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
