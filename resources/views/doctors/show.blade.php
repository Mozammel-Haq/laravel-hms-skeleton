<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Doctor Profile</h3>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-xl rounded-circle me-3">
                                <img src="{{ asset('assets') }}/img/doctors/doctor-01.jpg" alt="img" class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">{{ optional($doctor->user)->name ?? 'Doctor' }}</div>
                                <div class="text-muted">{{ optional($doctor->department)->name ?? 'Department' }}</div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted">Specialization</div>
                                <div class="fw-semibold">{{ $doctor->specialization ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">License</div>
                                <div class="fw-semibold">{{ $doctor->license_number ?? 'N/A' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted">Status</div>
                                <span class="badge bg-{{ ($doctor->status ?? 'inactive') === 'active' ? 'success' : 'secondary' }}">{{ $doctor->status ?? 'inactive' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div class="fw-semibold">Schedules</div>
                        <a href="{{ route('doctors.schedule', $doctor) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Day</th>
                                        <th>Start</th>
                                        <th>End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($doctor->schedules as $s)
                                        <tr>
                                            <td>{{ $s->id }}</td>
                                            <td>{{ $s->day ?? $s->weekday ?? 'N/A' }}</td>
                                            <td>{{ $s->start_time ?? 'N/A' }}</td>
                                            <td>{{ $s->end_time ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No schedules configured</td>
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
</x-app-layout>
