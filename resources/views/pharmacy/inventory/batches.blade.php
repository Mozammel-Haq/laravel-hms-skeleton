<x-app-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 bg-primary-subtle text-primary px-4 py-3 pt-3">
            <div>
                <h4 class="mb-1">Inventory Batches</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pharmacy.index') }}" class="text-decoration-none">Pharmacy</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Batches</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-journal-medical me-1"></i> Medicine Catalog
                </a>
                <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Batch
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('pharmacy.inventory.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-medium">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Medicine, Batch Number..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-medium">Status</label>
                            <select name="status" class="form-select">
                                <option value="all">All Statuses</option>
                                <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-medium">From Date</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-medium">To Date</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-light flex-grow-1">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Batches Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Medicine</th>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Batch Info</th>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Expiry Date</th>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Quantity</th>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Purchase Price</th>
                                <th class="px-4 py-3  small text-uppercase fw-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="bi bi-capsule"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-dark">{{ $batch->medicine->name }}</div>
                                                <div class="small text-muted">{{ $batch->medicine->strength }} - {{ $batch->medicine->dosage_form }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-dark fw-medium">#{{ $batch->batch_number }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="{{ $batch->expiry_date->isPast() ? 'text-danger fw-bold' : ($batch->expiry_date->diffInDays(now()) < 30 ? 'text-warning fw-bold' : 'text-dark') }}">
                                            {{ $batch->expiry_date->format('M d, Y') }}
                                        </div>
                                        <div class="small text-muted">{{ $batch->expiry_date->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge {{ $batch->quantity_in_stock > 10 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} rounded-pill px-3">
                                            {{ $batch->quantity_in_stock }} Units
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-medium text-dark">${{ number_format($batch->purchase_price, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($batch->expiry_date->isPast())
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Expired</span>
                                        @elseif($batch->quantity_in_stock == 0)
                                            <span class="badge bg-secondary-subtle  border border-secondary-subtle rounded-pill px-3">Out of Stock</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-5 text-center">
                                        <div class="mb-3">
                                            <div class="avatar-lg bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                                <i class="fa-solid fa-box fa-2x text-muted"></i>
                                            </div>
                                        </div>
                                        <h6 class=" mb-2">No Batches Found</h6>
                                        <p class="text-muted small mb-3">Try adjusting your filters or add a new batch.</p>
                                        <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-lg me-1"></i> Add Batch
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($batches->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $batches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
