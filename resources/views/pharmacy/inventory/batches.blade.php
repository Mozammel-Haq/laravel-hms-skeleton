<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Inventory Batches</h4>
                <p class="text-muted mb-0">Manage medicine batches and stock levels</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Batch
                </a>
                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-list me-1"></i> Medicine Catalog
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Medicine</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $batch->medicine->name }}</div>
                                        <div class="small text-muted">{{ $batch->medicine->strength }} - {{ $batch->medicine->dosage_form }}</div>
                                    </td>
                                    <td>{{ $batch->batch_number }}</td>
                                    <td>
                                        <div class="{{ $batch->expiry_date->isPast() ? 'text-danger fw-bold' : ($batch->expiry_date->diffInDays(now()) < 30 ? 'text-warning fw-bold' : '') }}">
                                            {{ $batch->expiry_date->format('M d, Y') }}
                                        </div>
                                        <div class="small text-muted">{{ $batch->expiry_date->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $batch->quantity_in_stock > 10 ? 'success' : 'danger' }}-subtle text-{{ $batch->quantity_in_stock > 10 ? 'success' : 'danger' }}">
                                            {{ $batch->quantity_in_stock }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($batch->purchase_price, 2) }}</td>
                                    <td>
                                        @if($batch->expiry_date->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($batch->quantity_in_stock == 0)
                                            <span class="badge bg-secondary">Out of Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No batches found</div>
                                        <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-sm btn-primary mt-2">Add First Batch</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $batches->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
