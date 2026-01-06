<x-app-layout>
    @php
        $payments = \App\Models\Payment::with(['invoice', 'patient'])
            ->latest()
            ->take(50)
            ->get();
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Payments</h3>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Billing</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Patient</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ optional($payment->invoice)->id }}</td>
                                    <td>{{ optional($payment->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->method ?? '—' }}</td>
                                    <td><span class="badge bg-success">{{ $payment->status ?? 'completed' }}</span></td>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No payments recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
