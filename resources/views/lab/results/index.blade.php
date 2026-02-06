<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Lab Test Results</h5>
                    {{-- breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('lab.catalog.index') }}">Lab Tests</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lab Test Results</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card-body p-3">

                <!-- Filter Form -->
                <div class="bg-light p-3 rounded mb-3">
                    <form method="GET" action="{{ route('lab.results.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search by Patient, Test, Result..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">From</label>
                                <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                    value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">To</label>
                                <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                    value="{{ request('to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                                    <a href="{{ route('lab.results.index') }}" class="btn btn-light btn-sm w-100">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Reported At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                                <tr>
                                    <td>{{ optional($result->order->patient)->name }}</td>
                                    <td>{{ optional($result->order->test)->name }}</td>
                                    <td>{{ $result->result_value ?? '' }}</td>
                                    <td>{{ optional($result->reported_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        @if ($result->order)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-icon" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            onclick="showResultModal({{ json_encode($result->result_value) }}, {{ json_encode($result->remarks ?? 'No remarks') }}, {{ json_encode(optional($result->order->test)->name) }})">
                                                            <i class="ti ti-file-description me-1"></i> View Result
                                                        </a>
                                                    </li>
                                                    @if ($result->pdf_path)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('lab.results.view', $result) }}"
                                                                target="_blank">
                                                                <i class="ti ti-eye me-1"></i> Preview Result
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('lab.results.download', $result) }}">
                                                                <i class="ti ti-download me-1"></i> Download Result
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('lab.show', $result->order) }}">
                                                            <i class="ti ti-eye me-1"></i> View Order
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No results found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $results->links() }}
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
