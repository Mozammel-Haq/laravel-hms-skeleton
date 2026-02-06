<x-app-layout>
    <div class="container-fluid py-4 px-4">
        <div class="card shadow-sm border-0">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-3 rounded-top">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Beds</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Beds</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('ipd.beds.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i>Add Bed
                </a>
            </div>

            <div class="card-body p-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('ipd.beds.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Bed Number..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied
                                </option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                    Maintenance</option>
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
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                                <a href="{{ route('ipd.beds.index') }}" class="btn btn-light btn-sm flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bed</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($beds as $bed)
                                <tr>
                                    <td><span class="fw-medium">{{ $bed->bed_number }}</span></td>
                                    <td>
                                        @php
                                            $statusClass = match($bed->status) {
                                                'available' => 'bg-success-subtle text-success',
                                                'occupied' => 'bg-danger-subtle text-danger',
                                                'maintenance' => 'bg-warning-subtle text-warning',
                                                default => 'bg-secondary-subtle text-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} border border-{{ str_replace(['bg-', '-subtle', 'text-'], '', $statusClass) }}">{{ ucfirst($bed->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ipd.beds.edit', $bed) }}">
                                                        <i class="ti ti-edit me-1"></i> Edit
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
                    {{ $beds->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
