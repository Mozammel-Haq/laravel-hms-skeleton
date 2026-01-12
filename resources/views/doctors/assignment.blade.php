<x-app-layout>
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="page-title mb-0">Doctor Clinic Assignments</h3>
                <p class="text-muted mb-0">Assign doctors to one or more clinics</p>
            </div>
            <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Add Doctor
            </a>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3" onsubmit="return false;">
                    <div class="col-md-4">
                        <label class="form-label">View Doctors by Clinic</label>
                        <select class="form-select" id="filterClinic">
                            <option value="">All Clinics</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Search Doctor</label>
                        <input type="text" class="form-control" id="searchDoctor"
                            placeholder="Doctor name or specialization">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary me-2" id="clearFilters">
                            Clear
                        </button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary">
                            Manage Doctors
                        </a>
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
                                <tr data-clinics="{{ $doctor->clinics->pluck('id')->implode(',') }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-light me-2">
                                                <span class="ti ti-user fs-20 p-2"></span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $doctor->user?->name }}</div>
                                                <div class="text-muted">{{ $doctor->license_number }}</div>
                                            </div>
                                        </div>
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
                                                        Assign Clinics – {{ $doctor->user?->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p class="text-muted mb-3">
                                                        Select the clinics where this doctor practices.
                                                    </p>

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
            </div>
        </div>

    </div>
</x-app-layout>
