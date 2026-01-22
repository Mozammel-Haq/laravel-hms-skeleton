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

                <form method="GET" action="{{ route('admin.schedule.requests.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Doctor Name or Email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="pending"
                                    {{ request('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
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
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('admin.schedule.requests.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
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
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <form
                                                        action="{{ route('admin.schedule.requests.approve', $request) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="ti ti-check me-1"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.schedule.requests.reject', $request) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-x me-1"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
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
