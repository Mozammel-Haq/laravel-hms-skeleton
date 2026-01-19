<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="d-flex justify-content-between align-items-center p-3 mt-2">
            <div>
                <h3 class="page-title mb-1">Sale Details</h3>
                <div class="text-muted">Sale #{{ $sale->id }} &bull; {{ $sale->created_at }}</div>
            </div>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <hr>

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
                                @foreach ($sale->items as $item)
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
                            <div
                                class="avatar avatar-md me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
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
                        @if (isset($invoice) && $invoice)
                            <div class="mb-2">
                                <span class="text-muted small text-uppercase">Invoice</span><br>
                                <span class="fw-semibold">{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small text-uppercase">Invoice Status</span><br>
                                <span
                                    class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($invoice->status) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @if (isset($invoice) && $invoice)
                            <a href="{{ route('billing.show', ['invoice' => $invoice->id, 'print' => 'true']) }}"
                                class="btn btn-outline-primary w-100 mb-2">
                                <i class="ti ti-printer me-1"></i> Print Invoice
                            </a>

                            {{-- Pharmacists might not have explicit process_payments permission, but should be able to pay --}}
                            {{-- @can('process_payments') --}}
                            <a href="{{ route('billing.payment.add', $invoice->id) }}" class="btn btn-success w-100">
                                <i class="ti ti-cash me-1"></i> Add Payment
                            </a>
                            {{-- @endcan --}}
                        @else
                            <button class="btn btn-secondary w-100 mb-2" disabled>
                                <i class="ti ti-printer me-1"></i> Invoice Not Generated
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
