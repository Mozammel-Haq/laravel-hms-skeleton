<x-app-layout>
    <div class="content">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </div>
            </div>
        </div>

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

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('appointments.booking.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Clinic</label>
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
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Department</label>
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
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-top: 28px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('appointments.booking.index') }}"
                                            class="btn btn-outline-secondary">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Doctors Grid -->
                        <div class="row">
                            @forelse($doctors as $doctor)
                                <div class="col-md-4 col-sm-6 col-lg-3">
                                    <div class="profile-widget">
                                        <div class="doctor-img text-center">
                                            <a href="{{ route('appointments.booking.show', $doctor) }}"
                                                class="avatar-xxl">
                                                <img class="avatar-img"
                                                    src="{{ $doctor->user->profile_photo_url ?? asset('assets/img/profiles/avatar-01.jpg') }}"
                                                    alt="User Image">
                                            </a>
                                        </div>
                                        <div class="dropdown profile-action">
                                            <a href="#" class="action-icon dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item"
                                                    href="{{ route('appointments.booking.show', $doctor) }}">Book
                                                    Now</a>
                                            </div>
                                        </div>
                                        <h4 class="doctor-name text-center">
                                            <a href="{{ route('appointments.booking.show', $doctor) }}">Dr.
                                                {{ $doctor->user->name }}</a>
                                        </h4>
                                        <div class="doc-prof text-center">{{ $doctor->department->name ?? 'General' }}
                                        </div>
                                        <div class="user-country text-center">
                                            <i class="fa fa-map-marker"></i>
                                            @foreach ($doctor->clinics as $clinic)
                                                {{ $clinic->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('appointments.booking.show', $doctor) }}"
                                                class="btn btn-primary btn-sm">Book Appointment</a>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                    <div class="col-12 text-center">
                                        <p>No doctors found matching your criteria.</p>
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
