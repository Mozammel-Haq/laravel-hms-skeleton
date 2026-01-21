<x-app-layout>
    <div class="container-fluid mx-2">

        <!-- Filter Form -->
        <div class="card mb-3 mt-2">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('doctor.schedule.index') }}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search Patient..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                    Scheduled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
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
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('doctor.schedule.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
