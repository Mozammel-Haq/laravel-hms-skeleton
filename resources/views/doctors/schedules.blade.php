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
                                        @php
                                            $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                            $activeDays = $doctor->schedules->pluck('day_of_week')->toArray();
                                        @endphp
                                        @foreach($days as $index => $day)
                                            <span class="badge {{ in_array($index, $activeDays) ? 'bg-success' : 'bg-secondary bg-opacity-25 text-dark' }} me-1">{{ $day }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($doctor->schedules->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($doctor->schedules->first()->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($doctor->schedules->first()->end_time)->format('H:i') }}
                                            @if($doctor->schedules->count() > 1)
                                                <small class="text-muted">(+{{ $doctor->schedules->count() - 1 }} more)</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not Set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctors.schedule', $doctor) }}"
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
