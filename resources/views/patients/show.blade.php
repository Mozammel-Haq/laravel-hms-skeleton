<x-app-layout>
    <div class="mb-3 mt-2 d-flex justify-content-between align-items-center card-body">
        <h6 class="fw-bold mb-0 d-flex align-items-center">
            <a href="{{ route('patients.index') }}" class="text-dark">
                <i class="ti ti-chevron-left me-1"></i> Patients
            </a>
        </h6>
        <div class="d-flex align-items-center gap-2">
            @can('update', $patient)
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-edit me-1"></i> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <div class="card mt-2 mb-4 mx-2 p-2">
        <div class="row align-items-end">
            <div class="col-xl-9 col-lg-8">
                <div class="d-sm-flex align-items-center position-relative overflow-hidden p-3">
                    @if ($patient->profile_photo)
                        <img src="{{ asset($patient->profile_photo) }}" alt="{{ $patient->name }}"
                            class="avatar avatar-xxxl patient-avatar me-3 flex-shrink-0 rounded object-fit-cover">
                    @else
                        <span
                            class="avatar avatar-xxxl patient-avatar me-3 flex-shrink-0 d-inline-flex align-items-center justify-content-center rounded bg-primary-subtle text-primary fs-1">
                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                        </span>
                    @endif
                    <div>
                        <p class="text-primary mb-1">{{ $patient->patient_code }}</p>
                        <h5 class="mb-1">
                            <span class="fw-bold">{{ $patient->name }}</span>
                        </h5>
                        <p class="mb-3">
                            {{ $patient->address ?: 'Address not provided' }}
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <p class="mb-0 d-inline-flex align-items-center">
                                <i class="ti ti-phone me-1 text-dark"></i>
                                <span class="text-dark">
                                    {{ $patient->phone ?: 'N/A' }}
                                </span>
                            </p>
                            <span class="mx-2 text-light d-none d-sm-inline">|</span>
                            @if ($patient->email)
                                <p class="mb-0 d-inline-flex align-items-center">
                                    <i class="ti ti-mail me-1 text-dark"></i>
                                    <span class="text-dark">
                                        {{ $patient->email }}
                                    </span>
                                </p>
                            @endif
                            @php
                                $lastVisit = optional($patient->appointments->sortByDesc('appointment_date')->first())
                                    ?->appointment_date;
                            @endphp
                            @if ($lastVisit)
                                <span class="mx-2 text-light d-none d-sm-inline">|</span>
                                <p class="mb-0 d-inline-flex align-items-center">
                                    <i class="ti ti-calendar-time me-1 text-dark"></i>
                                    Last Visited :
                                    <span class="text-dark ms-1">
                                        {{ \Carbon\Carbon::parse($lastVisit)->format('d M Y') }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="p-3 text-lg-end">
                    <div class="mb-3">
                        @if ($patient->phone)
                            <a href="tel:{{ $patient->phone }}"
                                class="btn btn-outline-light shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2">
                                <i class="ti ti-phone"></i>
                            </a>
                        @endif
                        @if ($patient->email)
                            <a href="mailto:{{ $patient->email }}"
                                class="btn btn-outline-light shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2">
                                <i class="ti ti-message-circle"></i>
                            </a>
                        @endif
                    </div>
                    <a href="{{ route('appointments.booking.index') }}" class="btn btn-primary">
                        <i class="ti ti-calendar-event me-1"></i> Book Appointment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 d-flex">
            <div class="card mx-2 p-2 flex-fill w-100 mb-4 mb-xl-0">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        <i class="ti ti-user-star me-1"></i> About
                    </h5>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                    <i class="ti ti-calendar-event text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">DOB</h6>
                                    <p class="mb-0">
                                        {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                    <i class="ti ti-droplet text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">Blood Group</h6>
                                    <p class="mb-0">{{ $patient->blood_group ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                    <i class="ti ti-gender-male text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">Gender</h6>
                                    <p class="mb-0 text-capitalize">{{ $patient->gender ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                    <i class="ti ti-mail text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">Email</h6>
                                    <p class="mb-0 text-break">{{ $patient->email ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                    <i class="ti ti-alert-circle text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">Emergency Contact</h6>
                                    <p class="mb-0">
                                        {{ $patient->emergency_contact_name ?: 'N/A' }}
                                        @if ($patient->emergency_contact_phone)
                                            ({{ $patient->emergency_contact_phone }})
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        <i class="ti ti-book me-1"></i> Vital Signs
                    </h5>
                </div>
                <div class="card-body pb-2">
                    @php
                        $latestVitals = $patient->vitals->sortByDesc('recorded_at')->first();
                    @endphp
                    @if ($latestVitals)
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-droplet fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">Blood Pressure</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                            {{ $latestVitals->blood_pressure ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-heart-rate-monitor fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">Heart Rate</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-danger fs-18"></i>
                                            {{ $latestVitals->heart_rate ? $latestVitals->heart_rate . ' bpm' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-hexagons fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">SPO2</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                            {{ $latestVitals->spo2 ? $latestVitals->spo2 . ' %' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-temperature fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">Temperature</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                            {{ $latestVitals->temperature ? $latestVitals->temperature . ' °C' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-activity fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">Respiratory Rate</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-danger fs-18"></i>
                                            {{ $latestVitals->respiratory_rate ? $latestVitals->respiratory_rate . ' rpm' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-weight fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">Weight</h6>
                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                            <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                            {{ $latestVitals->weight ? $latestVitals->weight . ' kg' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="mb-0 text-muted">No vitals recorded for this patient.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs nav-bordered m-3" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="#appointments" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent active"
                aria-selected="true" role="tab">
                <span>Appointments</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="#transactions" data-bs-toggle="tab" aria-expanded="false" class="nav-link bg-transparent"
                aria-selected="false" role="tab">
                <span>Transactions</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="#medical" data-bs-toggle="tab" aria-expanded="false" class="nav-link bg-transparent"
                aria-selected="false" role="tab">
                <span>Medical History</span>
            </a>
        </li>
    </ul>

    <div class="tab-content m-3">
        <div class="tab-pane active show" id="appointments" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient->appointments as $appointment)
                            <tr>
                                <td>
                                    {{ $appointment->appointment_date }}
                                </td>
                                <td>
                                    @if ($appointment->doctor)
                                        <a href="{{ route('doctors.show', $appointment->doctor) }}"
                                            class="d-flex align-items-center text-decoration-none text-body">
                                            <div class="avatar avatar-sm me-2 flex-shrink-0">
                                                <img src="{{ $appointment->doctor->profile_photo ? asset($appointment->doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                    alt="{{ optional($appointment->doctor->user)->name ?? 'Doctor' }}"
                                                    class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover;">
                                            </div>
                                            <div>
                                                <h6 class="fs-14 mb-1 text-truncate">
                                                    {{ optional($appointment->doctor->user)->name ?? 'Doctor' }}
                                                </h6>
                                                <p class="mb-0 fs-13 text-truncate">
                                                    {{ optional($appointment->doctor->department)->name ?? 'Department' }}
                                                </p>
                                            </div>
                                        </a>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2 flex-shrink-0">
                                                <img src="{{ asset('assets/img/doctors/doctor-01.jpg') }}"
                                                    alt="Doctor" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover;">
                                            </div>
                                            <div>
                                                <h6 class="fs-14 mb-1 text-truncate">
                                                    Doctor
                                                </h6>
                                                <p class="mb-0 fs-13 text-truncate">
                                                    Department
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ ucfirst($appointment->visit_type ?? 'new') }}</td>
                                <td>
                                    @php
                                        $status = strtolower($appointment->status ?? '');
                                        $map = [
                                            'confirmed' => ['success', 'success'],
                                            'checked in' => ['warning', 'warning'],
                                            'checked out' => ['info', 'info'],
                                            'cancelled' => ['danger', 'danger'],
                                            'scheduled' => ['primary', 'primary'],
                                        ];
                                        $colors = $map[$status] ?? ['secondary', 'secondary'];
                                    @endphp
                                    <span
                                        class="badge fs-13 badge-soft-{{ $colors[0] }} rounded text-{{ $colors[1] }} fw-medium">
                                        {{ ucfirst($appointment->status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                        class="btn btn-sm btn-light">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No appointments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="transactions" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Description</th>
                            <th>Paid Date</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient->invoices as $invoice)
                            @php
                                $payment = $invoice->payments->sortByDesc('paid_at')->first();
                            @endphp
                            <tr>
                                <td>#{{ $invoice->invoice_number }}</td>
                                <td class="text-dark">
                                    {{ optional($invoice->items->first())->description ?? 'Invoice' }}
                                </td>
                                <td class="text-dark">
                                    {{ optional($payment?->paid_at)->format('d M Y') ?? '-' }}
                                </td>
                                <td class="text-dark">
                                    @php
                                        $method = $payment?->payment_method ?? null;
                                        $methodLabels = [
                                            'cash' => 'Cash',
                                            'card' => 'Card',
                                            'mobile_banking' => 'Mobile Banking',
                                            'bank_transfer' => 'Bank Transfer',
                                        ];
                                    @endphp
                                    {{ $method ? $methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method)) : '-' }}
                                </td>
                                <td class="text-dark">
                                    {{ number_format($invoice->total_amount, 2) }}
                                </td>
                                <td>
                                    @php
                                        $status = $invoice->status;
                                        $statusMap = [
                                            'paid' => ['success', 'Completed'],
                                            'partial' => ['warning', 'Partial'],
                                            'unpaid' => ['danger', 'Unpaid'],
                                            'cancelled' => ['danger', 'Cancelled'],
                                        ];
                                        $meta = $statusMap[$status] ?? ['primary', ucfirst($status)];
                                    @endphp
                                    <span class="badge bg-{{ $meta[0] }}">
                                        {{ $meta[1] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="medical" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead>
                        <tr>
                            <th>Condition</th>
                            <th>Diagnosed Date</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient->medicalHistory as $history)
                            <tr>
                                <td>{{ $history->condition_name }}</td>
                                <td>
                                    {{ optional($history->diagnosed_date)->format('d M Y') ?? '-' }}
                                </td>
                                <td>
                                    @php
                                        $hStatus = $history->status ?? 'unknown';
                                        $hColor = match ($hStatus) {
                                            'active', 'cured', 'treated' => 'success',
                                            'ongoing', 'chronic' => 'warning',
                                            'critical' => 'danger',
                                            default => 'primary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $hColor }}">
                                        {{ ucfirst($hStatus) }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($history->notes, 80) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No medical history records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
