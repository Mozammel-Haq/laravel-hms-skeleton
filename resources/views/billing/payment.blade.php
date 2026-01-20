<x-app-layout>
    <div class="container-fluid mx-2">


        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3 mt-2">
                    <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Record Payment</h3>
            <a href="{{ route('billing.show', $invoice) }}" class="btn btn-outline-secondary">Back to Invoice</a>
        </div>
        <hr>
                        <div class="mb-2">
                            <strong>Invoice:</strong> {{ $invoice->invoice_number ?? $invoice->id }}
                        </div>
                        <div class="mb-2">
                            <strong>Patient:</strong> {{ optional($invoice->patient)->full_name ?? 'Patient' }}
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Total Amount:</strong> {{ number_format($invoice->total_amount ?? 0, 2) }}
                        </div>
                        @php
                            $totalPaid = $invoice->payments->sum('amount');
                            $remaining = max(($invoice->total_amount ?? 0) - $totalPaid, 0);
                        @endphp
                        <div class="mb-0">
                            <strong>Remaining Due:</strong> {{ number_format($remaining, 2) }}
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body">
                        <form method="POST" action="{{ route('billing.payment.store', $invoice) }}" class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label">Amount</label>
                                <input type="number" name="amount" step="0.01" min="0.01"
                                    max="{{ $remaining }}" value="{{ old('amount', $remaining) }}"
                                    class="form-control @error('amount') is-invalid @enderror">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method"
                                    class="form-select @error('payment_method') is-invalid @enderror">
                                    <option value="">Select method</option>
                                    <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                                    <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Save Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="mb-3">Existing Payments</h5>
                        <ul class="list-group">
                            @forelse ($invoice->payments as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        {{ \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i') }}
                                        ({{ $p->payment_method }})
                                    </span>
                                    <span>{{ number_format($p->amount, 2) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">No payments recorded yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

