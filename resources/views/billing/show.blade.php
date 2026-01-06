<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Invoice {{ $invoice->invoice_number }}</h3>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Patient:</strong> {{ optional($invoice->patient)->full_name ?? 'Patient' }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($invoice->status) }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal</th>
                                        <th>{{ number_format($invoice->subtotal, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Discount</th>
                                        <th>{{ number_format($invoice->discount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Tax</th>
                                        <th>{{ number_format($invoice->tax, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th>{{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Payments</h5>
                        <ul class="list-group">
                            @forelse ($invoice->payments as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i') }} ({{ $p->payment_method }})
                                    <span>{{ number_format($p->amount, 2) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">No payments yet.</li>
                            @endforelse
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('billing.payment.add', $invoice->id) }}" class="btn btn-primary w-100">Add Payment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
