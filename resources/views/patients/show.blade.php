<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Patient Profile</h3>
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-xl rounded-circle me-3">
                                <img src="{{ asset('assets') }}/img/users/user-01.jpg" alt="img" class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">{{ $patient->full_name ?? $patient->name ?? 'Patient' }}</div>
                                <div class="text-muted">#{{ $patient->id }}</div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted">Gender</div>
                                <div class="fw-semibold">{{ $patient->gender ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">DOB</div>
                                <div class="fw-semibold">{{ isset($patient->dob) ? \Illuminate\Support\Carbon::parse($patient->dob)->format('Y-m-d') : 'N/A' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted">Contact</div>
                                <div class="fw-semibold">{{ $patient->phone ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-appointments">Appointments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-admissions">Admissions</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-appointments">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($patient->appointments as $a)
                                                <tr>
                                                    <td>{{ $a->appointment_date ?? $a->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>{{ optional($a->doctor?->user)->name ?? 'Doctor' }}</td>
                                                    <td><span class="badge bg-secondary">{{ $a->status ?? 'scheduled' }}</span></td>
                                                    <td class="text-end"><a href="{{ route('appointments.show', $a) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No appointments</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-admissions">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Admitted</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($patient->admissions as $adm)
                                                <tr>
                                                    <td>{{ $adm->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>{{ optional($adm->doctor?->user)->name ?? 'Doctor' }}</td>
                                                    <td><span class="badge bg-success">{{ $adm->status ?? 'admitted' }}</span></td>
                                                    <td class="text-end"><a href="{{ route('ipd.show', $adm) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No admissions</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
