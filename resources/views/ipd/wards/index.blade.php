<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Wards</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wards</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('ipd.wards.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>Add Ward
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-bottom mt-0">
            <div class="card-body p-3">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('ipd.wards.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Ward Name..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="all">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
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
                                <a href="{{ route('ipd.wards.index') }}" class="btn btn-light btn-sm flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Floor</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wards as $ward)
                                <tr>
                                    <td><span class="fw-medium">{{ $ward->name }}</span></td>
                                    <td>{{ ucfirst($ward->type) }}</td>
                                    <td>{{ $ward->floor }}</td>
                                    <td>
                                        @php
                                            $statusClass = $ward->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }} border border-{{ str_replace(['bg-', '-subtle', 'text-'], '', $statusClass) }}">{{ ucfirst($ward->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ipd.wards.edit', $ward) }}">
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
                    {{ $wards->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
