<x-app-layout>
    <div class="container-fluid mx-2 mt-2">


        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3 rounded shadow-sm">
            <div>
                <h4 class="font-bold mb-2 text-primary">Medicine Inventory</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('pharmacy.index') }}">Pharmacy</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Medicine
                </a>
                <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-package me-1"></i> Manage Batches
                </a>
            </div>
        </div>
        <hr>
                <!-- Filter Form -->
                <form method="GET" action="{{ route('pharmacy.medicines.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trash
                                </option>
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
                                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-sm btn-light border flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Medicine Name</th>
                                <th>Generic Name</th>
                                <th>Manufacturer</th>
                                <th>Type</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicines as $medicine)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $medicine->name }}</div>
                                        <div class="small text-muted">{{ $medicine->strength }}</div>
                                    </td>
                                    <td>{{ $medicine->generic_name ?? '-' }}</td>
                                    <td>{{ $medicine->manufacturer ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $medicine->dosage_form ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($medicine->batches_sum_quantity_in_stock > 0)
                                            <span class="badge bg-success-subtle text-success">{{ $medicine->batches_sum_quantity_in_stock }}</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($medicine->price, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $medicine->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $medicine->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($medicine->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm btn-light" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('pharmacy.medicines.edit', $medicine) }}">
                                                        <i class="ti ti-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('pharmacy.medicines.destroy', $medicine) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-trash me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">No medicines found</div>
                                        <a href="{{ route('pharmacy.medicines.create') }}"
                                            class="btn btn-sm btn-primary mt-2">Add First Medicine</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
