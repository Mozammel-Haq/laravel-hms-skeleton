<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        {{-- Page Header --}}


        <div class="card border-0 p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3">
            <div>
                <h4 class="fw-bold mb-2 text-primary">Visits Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Visits</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('visits.create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus me-1"></i> New Visit
            </a>
        </div>
        <hr>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('visits.index') }}" class="mb-4 px-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Patient, Visit ID..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">From Date</label>
                        <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                            value="{{ request('from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">To Date</label>
                        <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                            value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                            <a href="{{ route('visits.index') }}" class="btn btn-sm btn-light border w-100">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="mt-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Appointment</th>
                        <th>Patient</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visits as $visit)
                        <tr>
                            <td>#{{ $visit->appointment_id }}</td>
                            <td>{{ optional($visit?->appointment?->patient)?->name }}</td>
                            <td>{{ $visit->visit_status }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($visit->trashed())
                                            <li>
                                                <form action="{{ route('visits.restore', $visit->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to restore this visit?')">
                                                        <i class="ti ti-refresh me-1"></i> Restore
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('visits.show', $visit) }}">
                                                    <i class="ti ti-eye me-1"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('visits.destroy', $visit) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this visit?')">
                                                        <i class="ti ti-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $visits->links() }}
        </div>
    </div>
</x-app-layout>
