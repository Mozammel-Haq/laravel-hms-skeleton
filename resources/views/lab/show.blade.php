<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Lab Order #{{ $order->id }}</h3>
            <a href="{{ route('lab.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Patient</div>
                        <div>{{ optional($order->patient)->full_name ?? optional($order->patient)->name ?? 'Patient' }}</div>
                        <div class="fw-semibold mt-3 mb-2">Test</div>
                        <div>{{ optional($order->test)->name ?? 'Test' }}</div>
                        <div class="fw-semibold mt-3 mb-2">Status</div>
                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div class="fw-semibold">Results</div>
                        @if ($order->status !== 'completed')
                            <a href="{{ route('lab.result.add', $order) }}" class="btn btn-sm btn-outline-primary">Add Result</a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Recorded At</th>
                                        <th>Value</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->results as $r)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Carbon::parse($r->recorded_at)->format('Y-m-d H:i') }}</td>
                                            <td>{{ $r->result_value }}</td>
                                            <td>{{ $r->notes ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No results recorded</td>
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
