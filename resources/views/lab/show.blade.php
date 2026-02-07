<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card p-3 mb-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded shadow-sm mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Lab Order Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Lab Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">#{{ $order->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('lab.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        </div>
        <div class="row g-3">
            <!-- Left Column: Patient & Order Info -->
            <div class="col-lg-4">
                <!-- Patient Card -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-user me-2 text-primary"></i> Patient
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md rounded-circle me-3 border border-secondary-subtle">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                    {{ strtoupper(substr(optional($order->patient)->name ?? 'P', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">
                                    {{ optional($order->patient)->full_name ?? (optional($order->patient)->name ?? 'Unknown Patient') }}
                                </h6>
                                <small class="text-muted">ID: #{{ optional($order->patient)->id }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details Card -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-flask me-2 text-primary"></i> Order Info
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted">Test Name</span>
                                <span class="fw-medium text-dark">{{ optional($order->test)->name ?? 'Unknown Test' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 py-2 border-bottom-0">
                                <span class="text-muted">Payment</span>
                                @if ($order->invoice)
                                    <span class="badge bg-{{ $order->invoice->status === 'paid' ? 'success' : ($order->invoice->status === 'partial' ? 'warning' : 'danger') }} rounded-pill">
                                        {{ ucfirst($order->invoice->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">No Invoice</span>
                                @endif
                            </li>
                        </ul>

                        <div class="mt-3">
                            @if ($order->invoice)
                                @if ($order->invoice->status !== 'paid')
                                    <a href="{{ route('billing.payment.add', $order->invoice) }}" class="btn btn-success w-100">
                                        <i class="ti ti-credit-card me-1"></i> Make Payment
                                    </a>
                                @else
                                    <div class="alert alert-success py-2 mb-0 text-center small">
                                        <i class="ti ti-check-circle me-1"></i> Payment Complete
                                    </div>
                                @endif
                            @else
                                <form action="{{ route('lab.invoice.generate', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-file-invoice me-1"></i> Generate Invoice
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Results -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-report-analytics me-2 text-primary"></i> Test Results
                        </h5>
                        @if ($order->status !== 'completed' && $order->invoice && $order->invoice->status === 'paid')
                            <a href="{{ route('lab.result.add', $order) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Result
                            </a>
                        @elseif($order->status !== 'completed')
                            <span class="text-muted small fst-italic">
                                <i class="ti ti-info-circle me-1"></i> Pay invoice to add results
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4">Reported At</th>
                                        <th>Value</th>
                                        <th>Remarks</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->results as $r)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-1 me-2 text-primary">
                                                        <i class="ti ti-calendar-time"></i>
                                                    </div>
                                                    <span>{{ \Illuminate\Support\Carbon::parse($r->reported_at)->format('d M Y, h:i A') }}</span>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-dark">{{ $r->result_value }}</td>
                                            <td>
                                                <span class="text-muted text-truncate d-inline-block" style="max-width: 150px;">
                                                    {{ $r->remarks ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-icon rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end border shadow-sm">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="showResultModal({{ json_encode($r->result_value) }}, {{ json_encode($r->remarks ?? 'No remarks') }}, {{ json_encode($order->test->name) }})">
                                                                <i class="ti ti-eye me-2 text-primary"></i> View Details
                                                            </a>
                                                        </li>
                                                        @if ($r->pdf_path)
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('lab.results.view', $r) }}" target="_blank">
                                                                    <i class="ti ti-file-text me-2 text-info"></i> Preview PDF
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('lab.results.download', $r) }}">
                                                                    <i class="ti ti-download me-2 text-success"></i> Download
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="mb-3">
                                                    <i class="ti ti-test-pipe fs-1 text-muted opacity-50"></i>
                                                </div>
                                                <p class="text-muted mb-0">No results recorded yet.</p>
                                            </td>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-flask me-2 text-primary"></i> Lab Result: <span id="modalTestName" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Result Value</label>
                        <div id="modalResultValue" class="p-3 bg-primary-subtle text-primary rounded border border-primary-subtle fw-bold fs-5"></div>
                    </div>
                    <div>
                        <label class="form-label text-muted small text-uppercase fw-bold">Remarks</label>
                        <div id="modalRemarks" class="p-3 bg-light rounded border border-secondary-subtle text-dark"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
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
