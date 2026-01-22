<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Visit #{{ $visit->id }}</h4>
                    <small class="text-muted">View visit details</small>
                </div>
            </div>
        </div>

        <!-- Visit Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header fw-semibold">Appointment Details</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">Appointment #</span><br>
                            <strong>{{ $visit->appointment_id }}</strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Patient</span><br>
                            <strong>{{ optional($visit->appointment->patient)->name }}</strong>
                        </div>
                        <div>
                            <span class="text-muted">Visit Status</span><br>
                            <span class="badge bg-warning text-white">
                                {{ ucfirst($visit->visit_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header fw-semibold">Consultation</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">Consultation ID</span><br>
                            <strong>{{ optional($visit->consultation)->id ?? '—' }}</strong>
                        </div>
                        <div>
                            <span class="text-muted">Diagnosis</span><br>
                            <strong>{{ optional($visit->consultation)->diagnosis ?? 'Not recorded' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices + Create Invoice -->
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">
                        Invoices for this Visit
                    </div>
                    <div class="card-body p-0">
                        @php
                            $invoices = \App\Models\Invoice::where('visit_id', $visit->id)->latest()->get();
                            $total = $invoices->sum('total_amount');
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>State</th>
                                        <th class="text-end">Total</th>
                                        <th>Issued</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $inv)
                                        <tr>
                                            <td>{{ $inv->invoice_number }}</td>
                                            <td>{{ $inv->invoice_type }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $inv->status }}
                                                </span>
                                            </td>
                                            <td>{{ $inv->state }}</td>
                                            <td class="text-end">
                                                {{ number_format($inv->total_amount, 2) }}
                                            </td>
                                            <td>{{ optional($inv->issued_at)->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('billing.show', $inv) }}">
                                                                <i class="ti ti-eye me-1"></i> View
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No invoices yet for this visit.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Visit Total</th>
                                        <th class="text-end">{{ number_format($total, 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Invoice -->
            <div class="col-lg-4">
                @can('create', \App\Models\Invoice::class)
                    <div class="card shadow-sm">
                        <div class="card-header fw-semibold">
                            Add Procedure / Service
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('visits.procedure.store', $visit) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control"
                                           value="1" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" name="unit_price"
                                           class="form-control" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Discount</label>
                                        <input type="number" name="discount"
                                               class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Tax (%)</label>
                                        <input type="number" name="tax"
                                               class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-sm">
                                    Generate Invoice
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>

    </div>
</x-app-layout>
