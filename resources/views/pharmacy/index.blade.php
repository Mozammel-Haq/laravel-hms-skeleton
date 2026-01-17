<x-app-layout>


    <div class="card border-0 mt-2 p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">Pharmacy Sales</h3>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('pharmacy.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('pharmacy.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('pharmacy.create') }}" class="btn btn-primary">
                    <i class="ti ti-shopping-cart-plus me-1"></i> New Sale (POS)
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sale ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->created_at }}</td>
                            <td>
                                <div class="fw-semibold">{{ $sale->patient->name }}</div>
                                <div class="small text-muted">{{ $sale->patient->patient_code }}</div>
                            </td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                @if ($sale->trashed())
                                    <form action="{{ route('pharmacy.restore', $sale->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Are you sure you want to restore this sale?')">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('pharmacy.show', $sale) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                    <form action="{{ route('pharmacy.destroy', $sale) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this sale?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
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
