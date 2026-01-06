<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Doctor Clinic Assignment</h3>
            <a href="{{ route('doctors.create') }}" class="btn btn-primary">Add Doctor</a>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Clinic</label>
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
                            placeholder="Name or specialization">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary me-2" id="clearFilters">Clear</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary">Manage Doctors</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Primary Department</th>
                                <th>Assigned Clinics</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="doctorTable">
                            @foreach ($doctors as $doctor)
                                <tr data-clinics="{{ $doctor->clinics->pluck('id')->implode(',') }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-light me-2">
                                                <span class="ti ti-user fs-20 p-2"></span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $doctor->user->name }}</div>
                                                <div class="text-muted">{{ $doctor->license_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $doctor->specialization }}</td>
                                    <td>{{ optional($doctor->primaryDepartment)->name ?? '—' }}</td>
                                    <td>
                                        @if ($doctor->clinics->isEmpty())
                                            <span class="badge bg-warning">None</span>
                                        @else
                                            @foreach ($doctor->clinics as $clinic)
                                                <span class="badge bg-secondary me-1">{{ $clinic->name }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctors.edit', $doctor->user_id) }}"
                                            class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                        <a href="{{ route('doctors.schedule', $doctor->user_id) }}"
                                            class="btn btn-sm btn-outline-success">Manage Schedule</a>
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
