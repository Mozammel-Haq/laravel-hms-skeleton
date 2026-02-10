<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card p-3 mb-0">
    <!-- Payment Status Alerts -->
    @if(request('payment_status') == 'success')
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <strong>Success!</strong> {{ request('message', 'Payment successful!') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(request('payment_status') == 'error')
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>Error!</strong> {{ request('message', 'Payment failed.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
        <div>
            <h5 class="fw-bold mb-1 text-primary">Admission Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-dots mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admission #{{ $admission->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
            @if ($admission->status === 'admitted')
                <a href="{{ route('ipd.assign-bed', $admission) }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-bed me-1"></i> Transfer Bed
                </a>

                @if (auth()->user()->hasRole('Doctor'))
                    @if (!$admission->discharge_recommended)
                        <form action="{{ route('ipd.recommend-discharge', $admission) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning text-white">
                                <i class="ti ti-check me-1"></i> Recommend Discharge
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="ti ti-check me-1"></i> Discharge Recommended
                        </button>
                    @endif
                @endif

                @if (auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin']))
                    <a href="{{ route('ipd.discharge', $admission) }}" class="btn btn-sm btn-danger">
                        <i class="ti ti-door-exit me-1"></i> Discharge
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>
    <div class="row g-4 mt-0">
            <!-- Left Column: Patient Profile -->
            <div class="col-lg-3">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            @if ($admission->patient && $admission->patient->profile_photo)
                                <img src="{{ asset($admission->patient->profile_photo) }}"
                                    alt="{{ $admission->patient->name }}"
                                    class="rounded-circle w-100 h-100 object-fit-cover border">
                            @else
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                                    {{ $admission->patient ? substr($admission->patient->name, 0, 1) : '?' }}
                                </span>
                            @endif
                        </div>
                        @if($admission->patient)
                            <h5 class="mb-1 fw-bold text-dark">{{ $admission->patient->name }}</h5>
                            <p class="text-muted small mb-3">{{ $admission->patient->patient_code }}</p>
                            <a href="{{ route('patients.show', $admission->patient) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Profile
                            </a>
                        @else
                            <h5 class="mb-1 fw-bold text-dark">Unknown Patient</h5>
                            <p class="text-muted small mb-3">N/A</p>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Gender</span>
                                <span class="fw-medium text-dark">{{ $admission->patient ? ucfirst($admission->patient->gender) : 'N/A' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Phone</span>
                                <span class="fw-medium text-dark">{{ $admission->patient->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Blood</span>
                                <span class="fw-medium text-dark">{{ $admission->patient->blood_group ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-2">
                        <h6 class="mb-0 fw-bold text-dark">Current Status</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted">Admission Status</span>
                            <span class="badge rounded-pill bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }} px-3 py-2">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3 pt-2 border-top">
                            <span class="text-muted">Total Deposit</span>
                            <span class="fw-bold text-dark">৳{{ number_format($admission->deposits->where('status', 'success')->sum('amount'), 2) }}</span>
                        </div>

                        @if($admission->status === 'admitted')
                            <div class="mb-3">
                                <label class="small text-muted mb-1">Add Deposit (Online)</label>
                                <form action="{{ route('online-payment.initiate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="admission_deposit">
                                    <input type="hidden" name="id" value="{{ $admission->id }}">

                                    <div class="mb-2">
                                        <select name="gateway" class="form-select form-select-sm">
                                            <option value="stripe">Stripe</option>
                                            <option value="sslcommerz">SSLCommerz</option>
                                        </select>
                                    </div>

                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="amount" class="form-control" placeholder="Amount" min="1" required>
                                        <button class="btn btn-success" type="submit">
                                            <i class="ti ti-credit-card me-1"></i> Pay
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @php
                            $currentAssignment = $admission->bedAssignments->whereNull('released_at')->last();
                        @endphp

                        @if ($currentAssignment)
                            <div class="p-3 bg-light rounded border border-light text-center mt-3">
                                <div class="text-muted small text-uppercase fw-bold mb-1">Assigned Bed</div>
                                <h4 class="mb-0 fw-bold text-primary">{{ $currentAssignment->bed->bed_number }}</h4>
                                <div class="small text-muted mt-1">
                                    {{ $currentAssignment->bed->room->room_number }} • {{ $currentAssignment->bed->room->ward->name }}
                                </div>
                            </div>
                        @else
                            @if ($admission->status === 'admitted')
                                <div class="alert alert-warning mb-0 border-0 d-flex align-items-center small">
                                    <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                    <div>No bed assigned.</div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="col-lg-9">
                <!-- Admission Info Grid -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark">Admission Information</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6 p-4 border-bottom border-end">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Attending Doctor</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2 rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                        <i class="ti ti-stethoscope fs-6"></i>
                                    </div>
                                    <span class="fw-medium text-dark">Dr. {{ $admission->doctor?->user?->name ?? 'Deleted Doctor' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 p-4 border-bottom">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Admission Date</label>
                                <span class="fw-medium text-dark fs-6">{{ $admission->admission_date }}</span>
                            </div>
                            <div class="col-md-6 p-4 border-end">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Discharge Date</label>
                                <span class="fw-medium text-dark">{{ $admission->discharge_date ?? 'Not Discharged' }}</span>
                            </div>
                            <div class="col-md-6 p-4 border-bottom">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Reason for Admission</label>
                                <span class="fw-medium text-dark">{{ $admission->admission_reason ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-12 p-4">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-3">Financial Overview</label>
                                <div class="row g-3">
                                    @php
                                        // Calculate Financials
                                        $invoice = $admission->invoice;
                                        $totalDeposited = $admission->deposits->where('status', 'success')->sum('amount');

                                        // Find Admission Fee Invoice
                                        $admissionFeeInvoices = \App\Models\Invoice::where('patient_id', $admission->patient_id)
                                            ->where('invoice_type', 'ipd_admission_fee')
                                            ->where(function($q) use ($admission) {
                                                $q->where('admission_id', $admission->id)
                                                  ->orWhereBetween('created_at', [
                                                      \Carbon\Carbon::parse($admission->admission_date)->subHours(12),
                                                      \Carbon\Carbon::parse($admission->admission_date)->addHours(24)
                                                  ]);
                                            })
                                            ->get();
                                        $admissionFeeTotal = $admissionFeeInvoices->sum('total_amount');
                                        $admissionFeePaid = 0;
                                        foreach($admissionFeeInvoices as $inv) {
                                             $admissionFeePaid += $inv->payments()->where('status', 'success')->sum('amount');
                                        }

                                        if ($invoice) {
                                            // Final Invoice + Admission Fee
                                            $totalBill = $invoice->total_amount + $admissionFeeTotal;

                                            $finalInvoicePaid = $invoice->payments->where('status', 'success')->sum('amount');
                                            $totalPaid = $finalInvoicePaid + $admissionFeePaid;

                                            $dueAmount = max(0, $totalBill - $totalPaid);

                                            $status = ucfirst($invoice->status);
                                            // Check if admission fee is effectively unpaid/partial
                                            if ($admissionFeeTotal > $admissionFeePaid && $status === 'Paid') {
                                                $status = 'Partial';
                                                $statusColor = 'warning';
                                            } else {
                                                $statusColor = $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'danger');
                                            }
                                            $billLabel = "Final Bill (+ Adm Fee)";
                                        } else {
                                            // Running Bill Estimation
                                            $serviceCharges = $admission->services->sum('total_price');
                                            $roomCharges = 0;
                                            foreach($admission->bedAssignments as $assignment) {
                                                $start = \Carbon\Carbon::parse($assignment->assigned_at);
                                                $end = $assignment->released_at ? \Carbon\Carbon::parse($assignment->released_at) : now();
                                                $days = max(1, $start->diffInDays($end));
                                                $roomCharges += $days * ($assignment->bed->room->daily_rate ?? 0);
                                            }
                                            $totalBill = $serviceCharges + $roomCharges + $admissionFeeTotal;
                                            $totalPaid = $totalDeposited + $admissionFeePaid;
                                            $dueAmount = max(0, $totalBill - $totalPaid);
                                            $status = 'Running';
                                            $statusColor = 'info';
                                            $billLabel = "Estimated Total Bill";
                                        }
                                    @endphp

                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block mb-1">{{ $billLabel }}</small>
                                        <span class="fw-bold text-dark fs-5">৳{{ number_format($totalBill, 2) }}</span>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block mb-1">Total Deposited</small>
                                        <span class="fw-bold text-primary fs-5">৳{{ number_format($totalDeposited, 2) }}</span>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block mb-1">Total Paid</small>
                                        <span class="fw-bold text-success fs-5">৳{{ number_format($totalPaid, 2) }}</span>
                                        @if($invoice && $totalPaid > $totalDeposited)
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">(Incl. payments)</small>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block mb-1">Due Amount</small>
                                        <span class="fw-bold text-danger fs-5">৳{{ number_format($dueAmount, 2) }}</span>
                                    </div>
                                    <div class="col-12 pt-2">
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded">
                                            <span class="text-muted small">Billing Status</span>
                                            <span class="badge bg-{{ $statusColor }} px-3">{{ $status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services / Procedures -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Services & Procedures</h6>
                        @if($admission->status === 'admitted')
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addServiceForm" aria-expanded="false" aria-controls="addServiceForm">
                                <i class="ti ti-plus me-1"></i> Add Service
                            </button>
                        @endif
                    </div>

                    <div class="collapse {{ $errors->any() ? 'show' : '' }}" id="addServiceForm">
                        <div class="card-body bg-light border-bottom" x-data="{ custom: false }">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="customServiceCheck" x-model="custom">
                                <label class="form-check-label user-select-none" for="customServiceCheck">
                                    Enter Custom Service / Procedure
                                </label>
                            </div>
                            <form action="{{ route('ipd.service.store', $admission) }}" method="POST" class="row g-3 align-items-end">
                                @csrf
                                <div class="col-md-6" x-show="!custom">
                                    <label class="form-label small text-muted fw-bold">Service/Procedure</label>
                                    <select name="service_id" class="form-select" :required="!custom">
                                        <option value="">Select Service...</option>
                                        @foreach($availableServices as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->name }} (৳{{ number_format($service->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4" x-show="custom" style="display: none;">
                                    <label class="form-label small text-muted fw-bold">Custom Service Name</label>
                                    <input type="text" name="custom_service_name" class="form-control" placeholder="e.g. Extra Care" :required="custom">
                                </div>
                                <div class="col-md-2" x-show="custom" style="display: none;">
                                    <label class="form-label small text-muted fw-bold">Price (৳)</label>
                                    <input type="number" name="custom_price" class="form-control" step="0.01" min="0" placeholder="0.00" :required="custom">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small text-muted fw-bold">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted fw-bold">Date</label>
                                    <input type="date" name="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Service</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Date</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom text-center">Qty</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom text-end">Price</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admission->services as $service)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="fw-medium text-dark">{{ $service->service_name }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-muted small">
                                            {{ \Carbon\Carbon::parse($service->service_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $service->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-end text-muted">
                                            ৳{{ number_format($service->unit_price, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-end fw-bold text-dark">
                                            ৳{{ number_format($service->total_price, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-muted">
                                            No services or procedures recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($admission->services->count() > 0)
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-end fw-bold text-muted">Total Service Charges</td>
                                        <td class="px-4 py-3 text-end fw-bold text-primary">
                                            ৳{{ number_format($admission->services->sum('total_price'), 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Lab Orders Card -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Lab Orders (During Admission)</h6>
                        @if($admission->status === 'admitted')
                            <a href="{{ route('lab.create', ['patient_id' => $admission->patient_id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-flask me-1"></i> Order Lab Test
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Test Name</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Date</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom text-center">Status</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($labOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="fw-medium text-dark">{{ $order->test->name }}</span>
                                            @if($order->results->isNotEmpty())
                                                <span class="badge bg-success-subtle text-success ms-2">Result Ready</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-muted small">
                                            {{ $order->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                             <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->status) }}
                                             </span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <a href="{{ route('lab.show', $order->id) }}" class="btn btn-sm btn-icon btn-ghost-primary" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-muted">
                                            No lab orders found during this admission.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lab Orders (Redundancy Check) -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Lab Orders (During Admission)</h6>
                        @if($admission->status === 'admitted')
                            <a href="{{ route('lab.create', ['patient_id' => $admission->patient_id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-flask me-1"></i> Order Lab Test
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if($labOrders->isEmpty())
                            <div class="p-4 text-center text-muted">
                                <i class="ti ti-test-pipe fs-2 mb-2 d-block"></i>
                                No lab orders found for this admission.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Date</th>
                                            <th>Test Name</th>
                                            <th>Status</th>
                                            <th class="text-end pe-4">Price</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labOrders as $order)
                                            <tr>
                                                <td class="ps-4 text-muted small">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                                <td class="fw-medium text-dark">
                                                    {{ $order->test->name ?? 'Unknown Test' }}
                                                    <br>
                                                    <small class="text-muted">{{ $order->test->code ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4 fw-bold">৳{{ number_format($order->test->price ?? 0, 2) }}</td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('lab.show', $order->id) }}" class="btn btn-sm btn-icon btn-ghost-primary" title="View Details">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bed History Table -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Bed Assignment History</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Bed / Location</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Assigned</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Released</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admission->bedAssignments->sortByDesc('assigned_at') as $assignment)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-dark">{{ $assignment->bed->bed_number }}</div>
                                            <div class="small text-muted">{{ $assignment->bed->room->room_number }} • {{ $assignment->bed->room->ward->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-secondary">{{ $assignment->assigned_at->format('M d, Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            @if ($assignment->released_at)
                                                <span class="text-secondary">{{ $assignment->released_at->format('M d, Y H:i') }}</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Current</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-secondary">
                                            @if ($assignment->released_at)
                                                {{ $assignment->assigned_at->diffForHumans($assignment->released_at, true) }}
                                            @else
                                                {{ $assignment->assigned_at->diffForHumans(null, true) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No bed assignments recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rounds & Vitals Split -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-dark">Doctor Rounds</h6>
                                @if ($admission->status === 'admitted')
                                    <a href="{{ route('ipd.rounds.create', $admission->id) }}" class="btn btn-sm btn-light border">
                                        <i class="ti ti-plus"></i> Add
                                    </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Date</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Doctor</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admission->rounds as $round)
                                            <tr>
                                                <td class="px-3 py-2 small">{{ $round->round_date }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $round->doctor?->user?->name ?? 'Unknown' }}</td>
                                                <td class="px-3 py-2 text-end">
                                                    <a href="{{ route('vitals.record', ['admission_id' => $admission->id, 'inpatient_round_id' => $round->id]) }}"
                                                       class="btn btn-sm btn-icon btn-ghost-primary"
                                                       title="Record Vitals">
                                                        <i class="ti ti-heart-rate-monitor"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted small">No rounds recorded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-dark">Recent Vitals</h6>
                                @if ($admission->status === 'admitted')
                                    <a href="{{ route('vitals.record', ['admission_id' => $admission->id]) }}" class="btn btn-sm btn-light border">
                                        <i class="ti ti-plus"></i> Add
                                    </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Time</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">BP</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Temp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admission->vitals->take(5) as $vital)
                                            <tr>
                                                <td class="px-3 py-2 small">{{ $vital->recorded_at?->format('H:i d/m') }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $vital->blood_pressure }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $vital->temperature }}°</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted small">No vitals recorded.</td>
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
    </div>
    </div>
    </div>
</x-app-layout>
