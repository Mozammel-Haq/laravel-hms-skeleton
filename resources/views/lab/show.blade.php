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
                                                <th>Remarks</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->results as $r)
                                                <tr>
                                                    <td>{{ \Illuminate\Support\Carbon::parse($r->reported_at)->format('Y-m-d H:i') }}</td>
                                                    <td>{{ $r->result_value }}</td>
                                                    <td>{{ $r->remarks ?? '—' }}</td>
                                                    <td class="text-end">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onclick="showResultModal({{ json_encode($r->result_value) }}, {{ json_encode($r->remarks ?? 'No remarks') }}, {{ json_encode($order->test->name) }})">
                                                                        <i class="ti ti-file-description me-1"></i> View Result
                                                                    </a>
                                                                </li>
                                                                @if($r->pdf_path)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('lab.results.view', $r) }}" target="_blank">
                                                                        <i class="ti ti-eye me-1"></i> Preview Result
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('lab.results.download', $r) }}">
                                                                        <i class="ti ti-download me-1"></i> Download Result
                                                                    </a>
                                                                </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No results recorded</td>
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

    <!-- Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lab Result: <span id="modalTestName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Result Value</label>
                        <p id="modalResultValue" class="p-2 bg-light rounded"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Remarks</label>
                        <p id="modalRemarks" class="p-2 bg-light rounded"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showResultModal(value, remarks, testName) {
            document.getElementById('modalResultValue').innerText = value;
            document.getElementById('modalRemarks').innerText = remarks;
            document.getElementById('modalTestName').innerText = testName;
            new bootstrap.Modal(document.getElementById('resultModal')).show();
        }
    </script>
    @endpush
</x-app-layout>
