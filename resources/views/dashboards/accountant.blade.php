<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-secondary rounded-circle me-2"><i class="ti ti-receipt"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Unpaid Invoices</p>
                                <h4 class="mb-0">{{ $cards['invoices_unpaid'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Unpaid</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-primary rounded-circle me-2"><i class="ti ti-check"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Paid Invoices</p>
                                <h4 class="mb-0">{{ $cards['invoices_paid'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-primary">Paid</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-info rounded-circle me-2"><i
                                    class="ti ti-currency-dollar"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Revenue This Month</p>
                                <h4 class="mb-0">{{ number_format($cards['revenue_month'], 2) }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-info">Month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Recent Invoices</h5>
                <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">Manage Billing</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Issued</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_number ?? $inv->id }}</td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $inv->status }}</span></td>
                                    <td>{{ $inv->created_at }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('billing.show', $inv) }}"
                                            class="btn btn-sm btn-primary">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
