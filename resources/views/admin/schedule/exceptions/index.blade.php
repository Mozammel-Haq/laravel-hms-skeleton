<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2 mx-2 p-3">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="page-title mb-0 text-primary">Schedule Exception Requests</h3>
                        <div class="text-muted text-sm">Review and approve doctor schedule changes</div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.schedule.exceptions.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search doctor or reason..." value="{{ request('search') }}">
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
                            <a href="{{ route('admin.schedule.exceptions.index') }}"
                                class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Date Range</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Reason</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exceptions as $exception)
                                <tr>
                                    <td>
                                        @if ($exception->doctor)
                                            <a href="{{ route('doctors.show', $exception->doctor) }}"
                                                class="d-flex align-items-center text-decoration-none text-body">
                                                <div class="avatar avatar-sm me-2">
                                                    <img src="{{ $exception->doctor->profile_photo ? asset($exception->doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                        alt="{{ $exception->doctor->user?->name ?? 'Doctor' }}"
                                                        class="rounded-circle"
                                                        style="width:32px;height:32px;object-fit:cover;">
                                                </div>
                                                <div>
                                                    <div class="fw-bold">
                                                        {{ $exception->doctor->user?->name ?? 'Deleted Doctor' }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ $exception->doctor->specialization ?? '' }}
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
                                            {{ \Carbon\Carbon::parse($exception->start_date)->format('M d, Y') }}
                                            @if ($exception->start_date != $exception->end_date)
                                                - {{ \Carbon\Carbon::parse($exception->end_date)->format('M d, Y') }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($exception->start_date)->format('l') }}
                                            @if ($exception->start_date != $exception->end_date)
                                                - {{ \Carbon\Carbon::parse($exception->end_date)->format('l') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($exception->is_available)
                                            <span class="badge bg-info-subtle text-info">Custom Hours</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Day Off</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($exception->is_available)
                                            {{ \Carbon\Carbon::parse($exception->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($exception->end_time)->format('H:i') }}
                                        @else
                                            <span class="text-muted">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($exception->reason)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                title="{{ $exception->reason }}">
                                                {{ $exception->reason }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
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
                                                        action="{{ route('admin.schedule.exceptions.update', $exception) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="ti ti-check me-1"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.schedule.exceptions.update', $exception) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
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
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ti ti-calendar-check fs-2 mb-2 d-block"></i>
                                            No pending schedule exception requests.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($exceptions->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $exceptions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
