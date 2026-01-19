<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2 mx-2 p-3">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="page-title mb-0 text-primary">Schedule Change Requests</h3>
                        <div class="text-muted text-sm">Review and approve doctor weekly schedule changes</div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable datatable-server">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Requested At</th>
                                <th>Summary</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        @if ($request->doctor)
                                            <a href="{{ route('doctors.show', $request->doctor) }}"
                                                class="d-flex align-items-center text-decoration-none text-body">
                                                <div class="avatar avatar-sm me-2">
                                                    <img src="{{ $request->doctor->profile_photo ? asset($request->doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                        alt="{{ $request->doctor->user?->name ?? 'Doctor' }}"
                                                        class="rounded-circle"
                                                        style="width:32px;height:32px;object-fit:cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-bold">
                                                        {{ $request->doctor->user?->name ?? 'Deleted Doctor' }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ $request->doctor->specialization ?? '' }}
                                                    </div>
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
                                                    <div class="fw-bold">Deleted Doctor</div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $request->created_at?->format('M d, Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $schedules = $request->schedules ?? [];
                                        @endphp
                                        @if (count($schedules))
                                            <div class="small">
                                                {{ count($schedules) }} slots configured
                                            </div>
                                            <ul class="list-unstyled small mb-0">
                                                @foreach (array_slice($schedules, 0, 3) as $slot)
                                                    <li>
                                                        @if (($slot['type'] ?? null) === 'weekly')
                                                            Day {{ $slot['day_of_week'] }},
                                                        @else
                                                            {{ $slot['schedule_date'] }},
                                                        @endif
                                                        {{ $slot['start_time'] }}–{{ $slot['end_time'] }}
                                                        ({{ $slot['slot_duration_minutes'] }} min)
                                                    </li>
                                                @endforeach
                                                @if (count($schedules) > 3)
                                                    <li>+ {{ count($schedules) - 3 }} more</li>
                                                @endif
                                            </ul>
                                        @else
                                            <span class="text-muted">No slots in request.</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.schedule.requests.approve', $request) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.schedule.requests.reject', $request) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ti ti-calendar-check fs-2 mb-2 d-block"></i>
                                            No pending schedule change requests.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($requests->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
