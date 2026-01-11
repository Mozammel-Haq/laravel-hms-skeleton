<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="page-title mb-1">Sale Details</h3>
                <div class="text-muted">Sale #{{ $sale->id }} &bull; {{ $sale->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Items Purchased</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->medicine->name ?? 'Unknown' }}</div>
                                            <div class="small text-muted">{{ $item->medicine->strength ?? '' }}</div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                    <td class="text-end fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Customer Info</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                {{ substr($sale->patient->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $sale->patient->name }}</h6>
                                <div class="small text-muted">{{ $sale->patient->patient_code }}</div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small text-uppercase">Prescription ID</span><br>
                            <span class="fw-semibold">#{{ $sale->prescription_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button class="btn btn-outline-primary w-100 mb-2">
                            <i class="ti ti-printer me-1"></i> Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
