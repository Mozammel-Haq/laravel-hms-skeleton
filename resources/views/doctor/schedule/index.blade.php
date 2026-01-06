<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">My Schedule</h3>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">View Appointments</a>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-muted">{{ optional($doctor)->specialization }}</div>
                            </div>
                            @if ($doctor)
                                <a href="{{ route('doctors.schedule', $doctor->user_id) }}"
                                    class="btn btn-primary">Manage Schedule</a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Morning</th>
                                        <th>Afternoon</th>
                                        <th>Evening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <tr>
                                            <td>{{ $day }}</td>
                                            <td>09:00–12:00</td>
                                            <td>14:00–17:00</td>
                                            <td>—</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Upcoming Appointments</div>
                        <ul class="list-group list-group-flush">
                            @forelse($appointments as $appt)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $appt->appointment_date }} {{ $appt->start_time }}</span>
                                    <span
                                        class="text-muted">{{ optional($appt->patient)->full_name ?? 'Patient' }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">No appointments scheduled.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
