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
                    <table class="table table-hover align-middle datatable">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchDoctor');
            var clinicSelect = document.getElementById('filterClinic');
            var clearButton = document.getElementById('clearFilters');
            var tableBody = document.getElementById('doctorTable');

            if (!searchInput || !clinicSelect || !clearButton || !tableBody) {
                return;
            }

            var rows = Array.prototype.slice.call(tableBody.querySelectorAll('tr'));

            function applyFilters() {
                var searchTerm = searchInput.value.toLowerCase().trim();
                var clinicId = clinicSelect.value;

                rows.forEach(function(row) {
                    var text = row.textContent.toLowerCase();
                    var clinicsAttr = row.getAttribute('data-clinics') || '';
                    var clinics = clinicsAttr.split(',').filter(Boolean);

                    var matchesSearch = !searchTerm || text.indexOf(searchTerm) !== -1;
                    var matchesClinic = !clinicId || clinics.indexOf(clinicId) !== -1;

                    row.style.display = (matchesSearch && matchesClinic) ? '' : 'none';
                });
            }

            searchInput.addEventListener('keyup', applyFilters);
            clinicSelect.addEventListener('change', applyFilters);

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                clinicSelect.value = '';
                applyFilters();
            });
        });
    </script>
</x-app-layout>
