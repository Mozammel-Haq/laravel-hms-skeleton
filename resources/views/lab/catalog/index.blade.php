<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Lab Test Catalog</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lab Test Catalog</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('lab.catalog.create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus me-1"></i> Add Lab Test
            </a>
        </div>

        <div class="card rounded-bottom mt-0 shadow-sm border-0">
            <div class="card-body p-3">
                <!-- Filter Form -->
                <div class="bg-light p-3 rounded mb-3">
                    <form method="GET" action="{{ route('lab.catalog.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search Test Name or Category..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">From</label>
                                <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                    value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">To</label>
                                <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                    value="{{ request('to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                                    <a href="{{ route('lab.catalog.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small text-uppercase text-muted">Name</th>
                                <th class="small text-uppercase text-muted">Category</th>
                                <th class="small text-uppercase text-muted">Price</th>
                                <th class="small text-uppercase text-muted">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $test->name }}</td>
                                    <td>{{ $test->category }}</td>
                                    <td>{{ number_format($test->price, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($test->status) {
                                                'active' => 'success',
                                                'inactive' => 'secondary',
                                                default => 'primary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">{{ ucfirst($test->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $tests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
