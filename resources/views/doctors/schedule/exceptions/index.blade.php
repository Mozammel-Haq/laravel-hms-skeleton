<x-app-layout>
    <div class="container-fluid mx-2 mt-2">


        <div class="card">
                <div class="bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3">
                    <h4 class="fw-bold mb-2 text-primary">My Schedule Exceptions</h4>
                    {{-- breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Schedule Exceptions</li>
                        </ol>
                    </nav>
                </div>
            <div class="card-body">

                <!-- Filters -->
                <form method="GET" action="{{ route('doctor.schedule.exceptions.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search reason..."
                                value="{{ request('search') }}">
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
                            <a href="{{ route('doctor.schedule.exceptions.index') }}"
                                class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                @if ($exceptions->count() > 0)
                    <div class="table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date Range</th>
                                    <th>Clinic</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exceptions as $exception)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($exception->start_date)->format('M d, Y') }}
                                            @if ($exception->start_date != $exception->end_date)
                                                - {{ \Carbon\Carbon::parse($exception->end_date)->format('M d, Y') }}
                                            @endif
                                        </td>
                                        <td>{{ $exception->clinic->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($exception->is_available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Day Off</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($exception->is_available)
                                                {{ \Carbon\Carbon::parse($exception->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($exception->end_time)->format('h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($exception->reason, 50) }}</td>
                                        <td>
                                            @if ($exception->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($exception->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($exception->status == 'pending')
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm btn-light btn-icon dropdown-toggle hide-arrow"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <form
                                                                action="{{ route('doctor.schedule.exceptions.destroy', $exception->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to cancel this request?')">
                                                                    <i class="ti ti-trash me-1"></i> Cancel Request
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $exceptions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-calendar-off fs-1 text-muted mb-3"></i>
                        <p class="text-muted">No schedule exceptions found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
