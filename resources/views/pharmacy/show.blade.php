<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row align-items-center mb-2 bg-primary-subtle text-primary px-4 py-3 pt-3">
            <div class="col">
                <h4 class="mb-1 fw-bold text-dark">Sale Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pharmacy.index') }}" class="text-decoration-none">Pharmacy</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sale #{{ $sale->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto d-flex gap-2">
                <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
                @if (isset($invoice) && $invoice)
                    <a href="{{ route('billing.show', ['invoice' => $invoice->id, 'print' => 'true']) }}"
                        class="btn btn-primary">
                        <i class="ti ti-printer me-1"></i> Print Invoice
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Items Purchased -->
            <div class="col-lg-8">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-shopping-cart me-2 text-primary"></i> Items Purchased
                        </h5>
                        <div class="text-muted small">
                            <i class="ti ti-calendar-time me-1"></i> {{ $sale->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4">Medicine</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold text-dark">{{ $item->medicine->name ?? 'Unknown' }}</div>
                                                <div class="small text-muted">{{ $item->medicine->strength ?? '' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end pe-4 fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end py-3 fw-bold text-dark">Grand Total</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-primary fs-5">{{ number_format($sale->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Customer Info & Actions -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-user me-2 text-info"></i> Customer Info
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-3 fw-bold border border-primary-subtle">
                                    {{ substr($sale->patient->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $sale->patient->name }}</h5>
                                <div class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                    {{ $sale->patient->patient_code }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 border border-secondary-subtle mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Prescription ID</label>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-prescription me-2 text-muted"></i>
                                <span class="fw-semibold text-dark">#{{ $sale->prescription_id ?? 'N/A' }}</span>
                            </div>
                        </div>

                        @if (isset($invoice) && $invoice)
                            <div class="bg-light rounded p-3 border border-secondary-subtle">
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase fw-bold mb-1">Invoice Number</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-file-invoice me-2 text-muted"></i>
                                        <span class="fw-semibold text-dark">{{ $invoice->invoice_number }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small text-uppercase fw-bold mb-1">Status</label>
                                    <div>
                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }} fs-6">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border border-secondary-subtle shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-settings me-2 text-secondary"></i> Actions
                        </h5>
                    </div>
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
