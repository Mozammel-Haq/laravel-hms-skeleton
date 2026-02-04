<x-app-layout>
    <div class="container-fluid py-2 px-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-2 bg-primary-subtle px-3 py-2 rounded">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Bed Assignments</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bed Assignments</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('ipd.bed_assignments.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Patient..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="all">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">From Date</label>
                            <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">To Date</label>
                            <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                                <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-light btn-sm flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bed</th>
                                <th>Patient</th>
                                <th>Assigned At</th>
                                <th>Released At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $assignment)
                                <tr>
                                    <td><span class="fw-medium">{{ optional($assignment->bed)->bed_number }}</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ optional($assignment->admission->patient)->name }}</span>
                                            <span class="small text-muted">{{ optional($assignment->admission->patient)->patient_code }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y H:i') : '-' }}</td>
                                    <td>{{ $assignment->released_at ? \Carbon\Carbon::parse($assignment->released_at)->format('M d, Y H:i') : '-' }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('ipd.bed_assignments.show', $assignment) }}">
                                                        <i class="ti ti-eye me-1"></i> View
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $assignments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
