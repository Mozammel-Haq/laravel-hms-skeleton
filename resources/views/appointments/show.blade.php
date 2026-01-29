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
                                                <span class="badge bg-warning-subtle text-warning fs-6">Partially
                                                    Paid</span>
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
                                        @if ($appointment->patient)
                                            <a href="{{ route('patients.show', $appointment->patient) }}"
                                                class="d-flex align-items-center mb-3 text-decoration-none text-body">
                                                <div class="avatar me-3">
                                                    @if ($appointment->patient->profile_photo)
                                                        <img src="{{ asset($appointment->patient->profile_photo) }}"
                                                            alt="{{ $appointment->patient->name }}"
                                                            class="rounded-circle"
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
                                            <div>
                                                <i class="ti ti-mail me-2 text-muted"></i>
                                                {{ $appointment->patient->email ?? 'N/A' }}
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar me-3">
                                                    <span class="avatar-title rounded-circle bg-secondary text-white">
                                                        ?
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">Unknown Patient</div>
                                                    <div class="text-muted small">N/A</div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <i class="ti ti-phone me-2 text-muted"></i> N/A
                                            </div>
                                            <div>
                                                <i class="ti ti-mail me-2 text-muted"></i> N/A
                                            </div>
                                        @endif
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
                                                    @php
                                                        $rawSpec = $appointment->doctor?->specialization;
                                                        $rawSpec = \Illuminate\Support\Arr::wrap($rawSpec);
                                                        $flat = \Illuminate\Support\Arr::flatten($rawSpec);
                                                        $pieces = [];
                                                        foreach ($flat as $item) {
                                                            if (!is_string($item) && !is_numeric($item)) {
                                                                continue;
                                                            }
                                                            foreach (explode(',', (string) $item) as $part) {
                                                                $part = trim($part);
                                                                if ($part !== '') {
                                                                    $pieces[] = $part;
                                                                }
                                                            }
                                                        }
                                                        $pieces = array_slice($pieces, 0, 2);
                                                    @endphp
                                                    {{ empty($pieces) ? 'N/A' : implode(', ', $pieces) }}
                                                </div>
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
                                        <span class="badge bg-info">Online</span>
                                    @else
                                        <span class="badge bg-secondary">In-Person Visit</span>
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
                                    <form action="{{ route('appointments.status.update', $appointment) }}"
                                        method="POST">
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

                                @if (
                                    $appointment->status !== 'cancelled' &&
                                        $appointment->status !== 'completed' &&
                                        $appointment->status !== 'confirmed')
                                    <form action="{{ route('appointments.status.update', $appointment) }}"
                                        method="POST">
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
