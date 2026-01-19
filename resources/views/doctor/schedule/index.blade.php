<x-app-layout>
    <div class="container-fluid mx-2">

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4 mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="page-title mb-0">My Schedule</h3>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">View
                                Appointments</a>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-muted">{{ optional($doctor)->specialization }}</div>
                            </div>
                            @if ($doctor)
                                <a href="{{ route('doctor.schedule.manage') }}" class="btn btn-primary">Manage
                                    Schedule</a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Morning</th>
                                        <th>Afternoon</th>
                                        <th>Evening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $days = [
                                            1 => 'Monday',
                                            2 => 'Tuesday',
                                            3 => 'Wednesday',
                                            4 => 'Thursday',
                                            5 => 'Friday',
                                            6 => 'Saturday',
                                            0 => 'Sunday',
                                        ];
                                    @endphp
                                    @foreach ($days as $index => $label)
                                        @php
                                            $daySchedules = $schedules->get($index, collect());
                                            $morning = [];
                                            $afternoon = [];
                                            $evening = [];

                                            foreach ($daySchedules as $schedule) {
                                                $start = $schedule->start_time;
                                                $end = $schedule->end_time;
                                                $startHour = (int) substr($start, 0, 2);
                                                $range = substr($start, 0, 5) . '–' . substr($end, 0, 5);

                                                if ($startHour < 12) {
                                                    $morning[] = $range;
                                                } elseif ($startHour < 17) {
                                                    $afternoon[] = $range;
                                                } else {
                                                    $evening[] = $range;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>{{ $morning ? implode(', ', array_unique($morning)) : '—' }}</td>
                                            <td>{{ $afternoon ? implode(', ', array_unique($afternoon)) : '—' }}</td>
                                            <td>{{ $evening ? implode(', ', array_unique($evening)) : '—' }}</td>
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
