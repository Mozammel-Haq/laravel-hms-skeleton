<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3 rounded shadow-sm">
            <div>
                <h4 class="font-bold mb-2 text-primary">Pharmacy Sales</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pharmacy Sales</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-box me-1"></i> Inventory
                </a>
                <a href="{{ route('pharmacy.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-shopping-cart-plus me-1"></i> New Sale (POS)
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('pharmacy.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search sale ID or patient..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">From Date</label>
                            <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">To Date</label>
                            <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
                                <a href="{{ route('pharmacy.index') }}" class="btn btn-sm btn-light border flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sale ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->created_at }}</td>
                            <td>
                                @if ($sale->patient)
                                    <a href="{{ route('patients.show', $sale->patient) }}"
                                        class="text-decoration-none text-body">
                                        <div class="fw-semibold">{{ $sale->patient->name }}</div>
                                        <div class="small text-muted">{{ $sale->patient->patient_code }}</div>
                                    </a>
                                @else
                                    <div class="text-muted">
                                        <div class="fw-semibold">Walk-in Customer</div>
                                        <div class="small text-muted">N/A</div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($sale->trashed())
                                            <li>
                                                <form action="{{ route('pharmacy.restore', $sale->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to restore this sale?')">
                                                        <i class="ti ti-refresh me-1"></i> Restore
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pharmacy.show', $sale) }}">
                                                    <i class="ti ti-eye me-1"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('pharmacy.destroy', $sale) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this sale?')">
                                                        <i class="ti ti-trash me-1"></i> Delete
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                No sales found. <a href="{{ route('pharmacy.create') }}">Create a new sale</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($sales->hasPages())
            <div class="card-footer bg-transparent">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
