<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Lab Order #{{ $order->id }}</h3>
                    <a href="{{ route('lab.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="fw-semibold mb-2">Patient</div>
                                <div>
                                    {{ optional($order->patient)->full_name ?? (optional($order->patient)->name ?? 'Patient') }}
                                </div>
                                <div class="fw-semibold mt-3 mb-2">Test</div>
                                <div>{{ optional($order->test)->name ?? 'Test' }}</div>
                                <div class="fw-semibold mt-3 mb-2">Status</div>
                                <span
                                    class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>

                                <div class="fw-semibold mt-3 mb-2">Payment Status</div>
                                @if ($order->invoice)
                                    <span class="badge bg-{{ $order->invoice->status === 'paid' ? 'success' : ($order->invoice->status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->invoice->status) }}
                                    </span>
                                    <div class="mt-2">
                                        @if ($order->invoice->status !== 'paid')
                    <a href="{{ route('billing.payment.add', $order->invoice) }}" class="btn btn-sm btn-success w-100">Make Payment</a>
                @else
                                            <span class="text-muted small"><i class="ti ti-check-circle"></i> Paid</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-secondary">No Invoice</span>
                                    <div class="mt-2">
                                        <form action="{{ route('lab.invoice.generate', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Generate Invoice</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div class="fw-semibold">Results</div>
                                @if ($order->status !== 'completed' && $order->invoice && $order->invoice->status === 'paid')
                                    <a href="{{ route('lab.result.add', $order) }}"
                                        class="btn btn-sm btn-outline-primary">Add Result</a>
                                @elseif($order->status !== 'completed')
                                    <span class="text-muted small">Pay invoice to add results</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Reported At</th>
                                                <th>Value</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->results as $r)
                                                <tr>
                                                    <td>{{ \Illuminate\Support\Carbon::parse($r->reported_at)->format('Y-m-d H:i') }}
                                                    </td>
                                                    <td>{{ $r->result_value }}</td>
                                                    <td>{{ $r->notes ?? '—' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No results
                                                        recorded</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>
