<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Lab Tests</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
                        <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Lab Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lab Tests</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('lab.create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-flask me-1"></i> Order Lab Test
            </a>
        </div>

        <div class="card shadow-sm rounded-bottom mt-0">
            <div class="card-body p-3">
                <!-- Filter Form -->
                <div class="bg-light p-3 rounded mb-3">
                    <form method="GET" action="{{ route('lab.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search by ID, Patient, Status..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="all">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed
                                    </option>
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
                                    <a href="{{ route('lab.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="small text-uppercase text-muted">#</th>
                                <th class="small text-uppercase text-muted">Patient</th>
                                <th class="small text-uppercase text-muted">Test</th>
                                <th class="small text-uppercase text-muted">Status</th>
                                <th class="small text-uppercase text-muted">Ordered</th>
                                <th class="text-end small text-uppercase text-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        @if ($order->patient)
                                            <a href="{{ route('patients.show', $order->patient) }}"
                                                class="text-decoration-none text-body fw-bold">
                                                {{ $order->patient->full_name ?? $order->patient->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Unknown Patient</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ optional($order->test)->name ?? 'Test' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->status;
                                            $color = match ($status) {
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>{{ isset($order->order_date) ? \Illuminate\Support\Carbon::parse($order->order_date)->format('M d, Y') : $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                @if ($order->trashed())
                                                    <li>
                                                        <form action="{{ route('lab.restore', $order->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"
                                                                onclick="return confirm('Are you sure you want to restore this order?')">
                                                                <i class="ti ti-refresh me-1"></i> Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('lab.show', $order) }}">
                                                            <i class="ti ti-eye me-2"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('lab.destroy', $order) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this order?')">
                                                                <i class="ti ti-trash me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-flask-off fs-1 mb-2"></i>
                                            <p class="mb-0">No lab orders found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
