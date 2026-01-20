<x-app-layout>
    <div class="card m-2">
        <div class="card-body">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Appointment Details</h4>
            <p class="text-muted">View appointment information</p>
        </div>
        <div class="action-btn">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary me-2">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
            @can('update', $appointment)
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <ul class="nav nav-tabs mb-4" id="appointmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                        type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="medical-history-tab" data-bs-toggle="tab"
                        data-bs-target="#medical-history" type="button" role="tab" aria-controls="medical-history"
                        aria-selected="false">Medical History</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions"
                        type="button" role="tab" aria-controls="prescriptions"
                        aria-selected="false">Prescriptions</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lab-reports-tab" data-bs-toggle="tab" data-bs-target="#lab-reports"
                        type="button" role="tab" aria-controls="lab-reports"
                        aria-selected="false">Lab Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices"
                        type="button" role="tab" aria-controls="invoices" aria-selected="false">Invoices</button>
                </li>
            </ul>

            <div class="tab-content" id="appointmentTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Appointment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted mb-1">Date & Time</label>
                                    <div class="fw-bold fs-5">
                                        {{ $appointment->appointment_date }}
                                    </div>
                                    <div class="text-primary">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <label class="text-muted mb-1">Status</label>
                                    <div class="mb-2">
                                        @php
                                            $status = $appointment->status;
                                            $color = match ($status) {
                                                'confirmed' => 'success',
                                                'arrived' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'warning',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }} fs-6">{{ ucfirst($status) }}</span>
                                    </div>

                                    <label class="text-muted mb-1">Consultation Payment</label>
                                    <div>
                                        @if ($consultationInvoice)
                                            @if ($consultationInvoice->status === 'paid')
                                                <span class="badge bg-success-subtle text-success fs-6">Paid</span>
                                            @elseif ($consultationInvoice->status === 'partial')
                                                <span
                                                    class="badge bg-warning-subtle text-warning fs-6">Partially Paid</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger fs-6">Unpaid</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fs-6">Not
                                                Invoiced</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <h6 class="text-muted mb-3">Patient Details</h6>
                                        <a href="{{ route('patients.show', $appointment->patient) }}"
                                            class="d-flex align-items-center mb-3 text-decoration-none text-body">
                                            <div class="avatar me-3">
                                                @if ($appointment->patient->profile_photo)
                                                    <img src="{{ asset($appointment->patient->profile_photo) }}"
                                                        alt="{{ $appointment->patient->name }}" class="rounded-circle"
                                                        style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ substr($appointment->patient->name, 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                                <div class="text-muted small">
                                                    {{ $appointment->patient->patient_code }}</div>
                                            </div>
                                        </a>
                                        <div class="mb-2">
                                            <i class="ti ti-phone me-2 text-muted"></i>
                                            {{ $appointment->patient->phone ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <i class="ti ti-mail me-2 text-muted"></i>
                                            {{ $appointment->patient->email ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <i class="ti ti-gender-male me-2 text-muted"></i>
                                            {{ ucfirst($appointment->patient->gender ?? 'N/A') }}
                                        </div>
                                        <div class="mb-2">
                                            <i class="ti ti-calendar me-2 text-muted"></i>
                                            {{ $appointment->patient->date_of_birth?->format('d M, Y') ?? 'N/A' }}
                                            ({{ $appointment->patient->age ?? 'N/A' }} years)
                                        </div>
                                        <div>
                                            <i class="ti ti-droplet me-2 text-muted"></i>
                                            Blood Group: {{ $appointment->patient->blood_group ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <h6 class="text-muted mb-3">Doctor Details</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar me-3">
                                                @if ($appointment->doctor && $appointment->doctor->profile_photo)
                                                    <img src="{{ asset($appointment->doctor->profile_photo) }}"
                                                        alt="{{ $appointment->doctor->user?->name }}"
                                                        class="rounded-circle"
                                                        style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <span class="avatar-title rounded-circle bg-info text-white">
                                                        {{ substr($appointment->doctor?->user?->name ?? 'D', 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    @if ($appointment->doctor)
                                                        <a href="{{ route('doctors.show', $appointment->doctor) }}"
                                                            class="text-decoration-none text-body">
                                                            {{ $appointment->doctor->user?->name ?? 'Deleted Doctor' }}
                                                        </a>
                                                    @else
                                                        Deleted Doctor
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $appointment->doctor?->specialization ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <i class="ti ti-building-hospital me-2 text-muted"></i>
                                            {{ $appointment->doctor?->department?->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <label class="fw-bold mb-2">Reason for Visit</label>
                                <p class="text-muted bg-light p-3 rounded">
                                    {{ $appointment->reason_for_visit ?? 'No reason provided.' }}
                                </p>
                            </div>

                            <div>
                                <label class="fw-bold mb-2">Appointment Type</label>
                                <div>
                                    @if ($appointment->appointment_type === 'online')
                                        <span class="badge bg-info">Online Consultation</span>
                                    @else
                                        <span class="badge bg-secondary">In-Person Visit</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical History Tab -->
                <div class="tab-pane fade" id="medical-history" role="tabpanel" aria-labelledby="medical-history-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Medical History</h5>
                        </div>
                        <div class="card-body">
                            @if ($appointment->patient->medicalHistory->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Condition</th>
                                                <th>Diagnosis Date</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appointment->patient->medicalHistory as $history)
                                                <tr>
                                                    <td>{{ $history->condition_name }}</td>
                                                    <td>{{ $history->diagnosed_date ? \Carbon\Carbon::parse($history->diagnosed_date)->format('d M, Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $history->status ?? 'N/A' }}</td>
                                                    <td>{{ $history->notes ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No medical history records found.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Prescriptions Tab -->
                <div class="tab-pane fade" id="prescriptions" role="tabpanel" aria-labelledby="prescriptions-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Prescriptions</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $prescriptions = collect();
                                if (
                                    $appointment->visit &&
                                    $appointment->visit->consultation &&
                                    $appointment->visit->consultation->prescriptions
                                ) {
                                    $prescriptions = $appointment->visit->consultation->prescriptions;
                                }
                            @endphp

                            @if ($prescriptions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Medicines</th>
                                                <th>Notes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prescriptions as $prescription)
                                                <tr>
                                                    <td>{{ $prescription->created_at->format('d M, Y') }}</td>
                                                    <td>
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($prescription->items as $item)
                                                                <li>
                                                                    {{ $item->medicine->name }}
                                                                    <small class="text-muted">
                                                                        ({{ $item->dosage }}, {{ $item->duration }})
                                                                    </small>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ Str::limit($prescription->notes, 50) ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('clinical.prescriptions.show', $prescription) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No prescriptions found for this appointment.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Lab Reports Tab -->
                <div class="tab-pane fade" id="lab-reports" role="tabpanel" aria-labelledby="lab-reports-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lab Reports</h5>
                        </div>
                        <div class="card-body">
                            @if ($labOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Ordered Date</th>
                                                <th>Status</th>
                                                <th>Results</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($labOrders as $order)
                                                <tr>
                                                    <td>{{ $order->test->name ?? 'N/A' }}</td>
                                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($order->results->count() > 0)
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach ($order->results as $result)
                                                                    <li>
                                                                        {{ Str::limit($result->result_value, 30) }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <span class="text-muted">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('lab.show', $order) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No lab reports found for this patient.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Invoices</h5>
                        </div>
                        <div class="card-body">
                            @if ($invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('d M, Y') : $invoice->created_at->format('d M, Y') }}
                                                    </td>
                                                    <td>{{ ucfirst($invoice->invoice_type) }}</td>
                                                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                                    <td>
                                                        @if ($invoice->status === 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                        @elseif($invoice->status === 'partial')
                                                            <span class="badge bg-warning">Partial</span>
                                                        @else
                                                            <span class="badge bg-danger">Unpaid</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('billing.show', $invoice) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">No invoices found for this appointment.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if ($appointment->status === 'pending')
                            <form action="{{ route('appointments.status.update', $appointment) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="arrived">
                                <button class="btn btn-info w-100 mb-2">
                                    <i class="ti ti-walk me-2"></i> Mark Arrived
                                </button>
                            </form>
                        @endif

                        @if ($appointment->status === 'arrived' && !$appointment->visit)
                            <form action="{{ route('visits.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                <button class="btn btn-warning w-100 mb-2">
                                    <i class="ti ti-file-invoice me-2"></i> Start Visit &amp; Generate Bill
                                </button>
                            </form>
                        @endif

                        @if ($appointment->visit)
                            <a href="{{ route('vitals.record', ['visit_id' => $appointment->visit->id, 'appointment_id' => $appointment->id]) }}"
                                class="btn btn-outline-success w-100 mb-2">
                                <i class="ti ti-heart-rate-monitor me-2"></i> Record Vitals
                            </a>
                        @endif

                        @if (isset($consultationInvoice) && $consultationInvoice)
                            <a href="{{ route('billing.show', $consultationInvoice) }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="ti ti-file-invoice me-2"></i> View Invoice
                            </a>
                        @endif

                        @if ($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                            <form action="{{ route('appointments.status.update', $appointment) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="ti ti-x me-2"></i> Cancel Appointment
                                </button>
                            </form>
                        @endif

                        @if ($appointment->status === 'confirmed')
                            <a href="{{ route('clinical.consultations.create', $appointment->id) }}"
                                class="btn btn-primary w-100 mt-2">
                                <i class="ti ti-stethoscope me-2"></i> Start Consultation
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
