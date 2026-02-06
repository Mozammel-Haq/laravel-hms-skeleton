<x-app-layout>
    <div class="container-fluid py-4">
        <div class="card p-3">
        <!-- Header -->
        <div class="row align-items-center mx-2 bg-primary-subtle text-primary px-4 py-3 pt-3">
            <div class="col">
                <h4 class="mb-1 fw-bold text-dark">Visit Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('visits.index') }}" class="text-decoration-none">Visits</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Visit #{{ $visit->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <a href="{{ route('visits.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
        </div>
        <div class="row g-4 mb-4">
            <!-- Appointment Details -->
            <div class="col-md-6">
                <div class="card border border-secondary-subtle shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-calendar-event me-2 text-primary"></i> Appointment Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-4 fw-bold border border-primary-subtle">
                                    {{ substr(optional($visit->appointment->patient)->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ optional($visit->appointment->patient)->name ?? 'Unknown Patient' }}</h5>
                                <div class="text-muted small">
                                    Appointment #{{ $visit->appointment_id }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 border border-secondary-subtle">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small text-uppercase fw-bold">Visit Status</span>
                                <span class="badge bg-{{ $visit->visit_status === 'completed' ? 'success' : ($visit->visit_status === 'cancelled' ? 'danger' : 'warning') }} fs-6">
                                    {{ ucfirst($visit->visit_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultation Details -->
            <div class="col-md-6">
                <div class="card border border-secondary-subtle shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-stethoscope me-2 text-info"></i> Consultation
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Consultation ID</label>
                            <div class="fw-semibold text-dark fs-5">
                                {{ optional($visit->consultation)->id ? '#' . $visit->consultation->id : '—' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-muted small text-uppercase fw-bold mb-1">Diagnosis</label>
                            <div class="p-3 bg-light rounded border border-secondary-subtle text-secondary">
                                {{ optional($visit->consultation)->diagnosis ?? 'No diagnosis recorded yet.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices + Create Invoice -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border border-secondary-subtle shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-file-invoice me-2 text-secondary"></i> Invoices
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $invoices = \App\Models\Invoice::where('visit_id', $visit->id)->latest()->get();
                            $total = $invoices->sum('total_amount');
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4">Invoice #</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>State</th>
                                        <th class="text-end">Total</th>
                                        <th>Issued</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $inv)
                                        <tr>
                                            <td class="ps-4 fw-medium text-dark">{{ $inv->invoice_number }}</td>
                                            <td>{{ ucfirst($inv->invoice_type) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'partial' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($inv->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ ucfirst($inv->state) }}</span>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($inv->total_amount, 2) }}</td>
                                            <td>{{ optional($inv->issued_at)->format('d M Y') }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('billing.show', $inv) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Invoice">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="ti ti-file-invoice-off fs-1 mb-2 opacity-50"></i>
                                                <p class="mb-0">No invoices found for this visit.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="border-top">
                                    <tr class="bg-light">
                                        <td colspan="4" class="text-end py-3 fw-bold text-dark">Visit Total</td>
                                        <td class="text-end py-3 fw-bold text-primary fs-5">{{ number_format($total, 2) }}</td>
                                        <td colspan="2"></td>
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
                    <div class="card border border-secondary-subtle shadow-sm h-100">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="ti ti-plus me-2 text-success"></i> Add Procedure / Service
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('visits.procedure.store', $visit) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="e.g. General Checkup" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Unit Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Discount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" name="discount" class="form-control" step="0.01" min="0" value="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Tax (%)</label>
                                        <div class="input-group">
                                            <input type="number" name="tax" class="form-control" step="0.01" min="0" value="0">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-file-plus me-1"></i> Generate Invoice
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
