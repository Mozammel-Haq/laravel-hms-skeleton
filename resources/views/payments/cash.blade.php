<x-app-layout>
    @php
        $invoices = \App\Models\Invoice::latest()->take(100)->get();
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Cash Payments</h3>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Billing</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Invoice</label>
                        <select class="form-select" required>
                            <option value="">Select invoice</option>
                            @foreach ($invoices as $inv)
                                <option value="{{ $inv->id }}">#{{ $inv->id }}
                                    {{ number_format($inv->total ?? 0, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reference</label>
                        <input type="text" class="form-control" placeholder="Receipt no.">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="button">Process Cash Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
