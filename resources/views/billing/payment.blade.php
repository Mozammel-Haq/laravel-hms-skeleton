<x-app-layout>
    @php
        $totalPaid = $invoice->payments->sum('amount');
        $totalAmount = $invoice->total_amount ?? ($invoice->total ?? 0);
        $remaining = max($totalAmount - $totalPaid, 0);
    @endphp

    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="page-title mb-0">Record Payment</h3>
                <p class="text-muted mb-0">Add payment for Invoice #{{ $invoice->invoice_number ?? $invoice->id }}</p>
            </div>
            <a href="{{ route('billing.show', $invoice) }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Invoice
            </a>
        </div>

        <div class="row">
            <!-- Left Column: Payment Form & Details -->
            <div class="col-12 col-lg-8">
                <!-- Invoice Summary Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light-subtle">
                        <h3 class="card-title m-0">Invoice Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small text-uppercase fw-bold">Invoice Number</label>
                                <div class="fw-bold fs-4">#{{ $invoice->invoice_number ?? $invoice->id }}</div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small text-uppercase fw-bold">Patient</label>
                                <div class="fw-bold fs-4">{{ optional($invoice->patient)->name ?? 'Guest' }}</div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small text-uppercase fw-bold">Status</label>
                                <div>
                                    <span
                                        class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }} fs-5">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded text-center">
                                    <label class="text-muted small">Total Amount</label>
                                    <div class="h2 mb-0">৳ {{ number_format($totalAmount, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-success-lt rounded text-center">
                                    <label class="text-success small fw-bold">Total Paid</label>
                                    <div class="h2 mb-0 text-success">৳ {{ number_format($totalPaid, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-danger-lt rounded text-center">
                                    <label class="text-danger small fw-bold">Remaining Due</label>
                                    <div class="h2 mb-0 text-danger">৳ {{ number_format($remaining, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form Card -->
                @if ($remaining > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title m-0">
                                <i class="ti ti-cash me-2"></i>New Payment
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('billing.payment.store', $invoice) }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" name="amount" step="0.01" min="0.01"
                                                max="{{ $remaining }}" value="{{ old('amount', $remaining) }}"
                                                class="form-control @error('amount') is-invalid @enderror">
                                        </div>
                                        @error('amount')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Enter the amount to be paid (Max:
                                            {{ number_format($remaining, 2) }})</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label required">Payment Method</label>
                                        <select name="payment_method"
                                            class="form-select @error('payment_method') is-invalid @enderror">
                                            <option value="">Select method</option>
                                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                                            <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                                            <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary w-100 py-2">
                                            <i class="ti ti-device-floppy me-2"></i> Process Payment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="ti ti-circle-check fs-2 me-3"></i>
                        <div>
                            <h4 class="alert-title mb-1">Fully Paid!</h4>
                            <div class="text-secondary">This invoice has been fully settled. No further payments are
                                required.</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Payment History -->
            <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title m-0">Payment History</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse ($invoice->payments()->latest()->get() as $p)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold fs-4">৳ {{ number_format($p->amount, 2) }}</span>
                                        <span
                                            class="badge bg-secondary-lt text-capitalize">{{ str_replace('_', ' ', $p->payment_method) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted small mt-2">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($p->paid_at)->format('d M Y, h:i A') }}
                                    </div>
                                    @if ($p->received_by)
                                        <div class="d-flex align-items-center text-muted small mt-1">
                                            <i class="ti ti-user me-1"></i>
                                            Received by: {{ optional($p->receiver)->name ?? 'Staff' }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-5 text-center text-muted">
                                    <i class="ti ti-receipt-off fs-1 mb-3 opacity-50"></i>
                                    <p class="mb-0">No payments recorded yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
