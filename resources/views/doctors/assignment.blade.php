<x-app-layout>
    <div class="container-fluid mx-2">

        {{-- Page Header --}}


        {{-- Filters --}}
        <div class="card mb-2 mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="page-title mb-0">Doctor Clinic Assignments</h3>
                        <p class="text-muted mb-0">Assign doctors to one or more clinics</p>
                    </div>
                    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Add Doctor
                    </a>
                </div>
                <hr>
                <form method="GET" action="{{ route('doctors.assignment') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="clinic_id" class="form-select">
                                <option value="">All Clinics</option>
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}"
                                        {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search Doctor..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Active</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted
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
                            <a href="{{ route('doctors.assignment') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Doctors Table --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Primary Department</th>
                                <th>Assigned Clinics</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody id="doctorTable">
                            @forelse ($doctors as $doctor)
                                <tr>
                                    <td>
                                        @if ($doctor->user)
                                            <a href="{{ route('doctors.show', $doctor) }}"
                                                class="d-flex align-items-center text-decoration-none text-body">
                                                <div class="avatar avatar-sm me-2">
                                                    <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                        alt="{{ $doctor->user->name }}" class="rounded-circle"
                                                        style="width:32px;height:32px;object-fit:cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ $doctor->user->name }}
                                                    </div>
                                                    <div class="text-muted">{{ $doctor->license_number }}</div>
                                                </div>
                                            </a>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <img src="{{ asset('assets/img/doctors/doctor-01.jpg') }}"
                                                        alt="Deleted Doctor" class="rounded-circle"
                                                        style="width:32px;height:32px;object-fit:cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">Deleted Doctor</div>
                                                    <div class="text-muted">{{ $doctor->license_number }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>{{ $doctor->specialization }}</td>

                                    <td>{{ $doctor->primaryDepartment?->name ?? '—' }}</td>

                                    <td>
                                        @if ($doctor->clinics->isEmpty())
                                            <span class="badge bg-warning">Not Assigned</span>
                                        @else
                                            @foreach ($doctor->clinics as $clinic)
                                                <span class="badge bg-secondary me-1 mb-1">
                                                    {{ $clinic->name }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal"
                                            data-bs-target="#assignClinicModal-{{ $doctor->id }}">
                                            Assign Clinics
                                        </button>

                                        <a href="{{ route('doctors.schedule', $doctor->id) }}"
                                            class="btn btn-sm btn-outline-success">
                                            Schedule
                                        </a>
                                    </td>
                                </tr>

                                {{-- Assignment Modal --}}
                                <div class="modal fade" id="assignClinicModal-{{ $doctor->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <form method="POST"
                                                action="{{ route('doctors.assignment.update', $doctor->id) }}">
                                                @csrf

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Assign Clinics – {{ $doctor->user?->name ?? 'Deleted Doctor' }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p class="text-muted mb-3">
                                                        Select the clinics where this doctor practices.
                                                    </p>

                                                    @role('Super Admin')
                                                        @foreach ($clinics as $clinic)
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="clinic_ids[]" value="{{ $clinic->id }}"
                                                                    id="doctor{{ $doctor->id }}clinic{{ $clinic->id }}"
                                                                    {{ $doctor->clinics->contains($clinic->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="doctor{{ $doctor->id }}clinic{{ $clinic->id }}">
                                                                    {{ $clinic->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        @php
                                                            $userClinicId = auth()->user()->clinic_id ?? null;
                                                        @endphp
                                                        @foreach ($clinics->where('id', $userClinicId) as $clinic)
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="clinic_ids[]" value="{{ $clinic->id }}"
                                                                    id="doctor{{ $doctor->id }}clinic{{ $clinic->id }}"
                                                                    {{ $doctor->clinics->contains($clinic->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="doctor{{ $doctor->id }}clinic{{ $clinic->id }}">
                                                                    {{ $clinic->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endrole
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        Save Assignments
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No doctors found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($doctors instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-3">
                        {{ $doctors->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
