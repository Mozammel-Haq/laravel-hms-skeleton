<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="page-title mb-0">Schedule Change Requests</h3>
                        <div class="text-muted">Review and approve doctor weekly schedule changes</div>
                    </div>
                </div>
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
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span
                                                    class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    {{ substr($request->doctor?->user?->name ?? '?', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    {{ $request->doctor?->user?->name ?? 'Deleted Doctor' }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $request->doctor?->specialization ?? '' }}</div>
                                            </div>
                                        </div>
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
