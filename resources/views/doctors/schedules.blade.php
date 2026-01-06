<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Doctor Schedules</h3>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Back to Doctors</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Availability</th>
                                <th>Time Slots</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $doctor->user->name }}</div>
                                        <div class="text-muted">{{ $doctor->specialization }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success me-1">Mon</span>
                                        <span class="badge bg-success me-1">Tue</span>
                                        <span class="badge bg-success me-1">Wed</span>
                                        <span class="badge bg-success me-1">Thu</span>
                                        <span class="badge bg-success me-1">Fri</span>
                                        <span class="badge bg-secondary me-1">Sat</span>
                                        <span class="badge bg-secondary me-1">Sun</span>
                                    </td>
                                    <td>09:00–12:00, 14:00–17:00</td>
                                    <td>
                                        <a href="{{ route('doctors.schedule', $doctor->user_id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit Schedule</a>
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
