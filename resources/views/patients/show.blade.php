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
                                    <i class="ti ti-id text-body fs-16"></i>
                                </span>
                                <div>
                                    <h6 class="fs-13 fw-bold mb-1">Identity</h6>
                                    <p class="mb-0">
                                        NID: {{ $patient->nid_number ?: 'N/A' }}
                                        @if ($patient->birth_certificate_number)
                                            , Birth Certificate: {{ $patient->birth_certificate_number }}
                                        @endif
                                        @if ($patient->passport_number)
                                            , Passport: {{ $patient->passport_number }}
                                        @endif
                                    </p>
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
            <div class="table">
                <table class="table table-hover align-middle datatable">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
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
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-icon" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('appointments.show', $appointment) }}">
                                                    <i class="ti ti-eye me-1"></i> View Details
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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
            <div class="table">
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
            <!-- Medical Conditions -->
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="ti ti-activity-heartbeat me-1"></i> Medical Conditions
                    </h5>
                    @can('update', $patient)
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal"
                            data-bs-target="#addConditionModal">
                            <i class="ti ti-plus me-1"></i> Add Condition
                        </button>
                    @endcan
                </div>
                <div class="table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Condition</th>
                                <th>Diagnosed Date</th>
                                <th>Diagnosed By</th>
                                <th>Status</th>
                                <th>Notes</th>
                                @can('update', $patient)
                                    <th class="text-end pe-4">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->medicalHistory as $history)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $history->condition_name }}</td>
                                    <td>{{ optional($history->diagnosed_date)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $history->doctor_name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $hStatus = $history->status ?? 'unknown';
                                            $hColor = match ($hStatus) {
                                                'active', 'cured', 'treated' => 'success',
                                                'ongoing', 'chronic' => 'warning',
                                                'critical' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge bg-{{ $hColor }} bg-opacity-10 text-{{ $hColor }} px-2 py-1">
                                            {{ ucfirst($hStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                            title="{{ $history->notes }}">
                                            {{ $history->notes ?? '-' }}
                                        </span>
                                    </td>
                                    @can('update', $patient)
                                        <td class="text-end pe-4">
                                            <form
                                                action="{{ route('patients.medical-history.condition.destroy', $history) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this condition?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light text-danger"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-clipboard-heart fs-1 text-muted opacity-50 mb-2"></i>
                                            <p class="mb-0">No medical conditions recorded.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Allergies -->
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="ti ti-alert-triangle me-1"></i> Allergies
                    </h5>
                    @can('update', $patient)
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal"
                            data-bs-target="#addAllergyModal">
                            <i class="ti ti-plus me-1"></i> Add Allergy
                        </button>
                    @endcan
                </div>
                <div class="table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Allergen</th>
                                <th>Severity</th>
                                <th>Reaction/Notes</th>
                                @can('update', $patient)
                                    <th class="text-end pe-4">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->allergies as $allergy)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $allergy->allergy_name }}</td>
                                    <td>
                                        @php
                                            $sevColor = match (strtolower($allergy->severity)) {
                                                'low' => 'success',
                                                'medium' => 'warning',
                                                'high', 'severe' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge bg-{{ $sevColor }} bg-opacity-10 text-{{ $sevColor }} px-2 py-1">
                                            {{ ucfirst($allergy->severity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                            title="{{ $allergy->notes }}">
                                            {{ $allergy->notes ?? '-' }}
                                        </span>
                                    </td>
                                    @can('update', $patient)
                                        <td class="text-end pe-4">
                                            <form
                                                action="{{ route('patients.medical-history.allergy.destroy', $allergy) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this allergy record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light text-danger"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-mood-empty fs-1 text-muted opacity-50 mb-2"></i>
                                            <p class="mb-0">No allergies recorded.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Surgeries -->
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="ti ti-cut me-1"></i> Surgeries
                    </h5>
                    @can('update', $patient)
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal"
                            data-bs-target="#addSurgeryModal">
                            <i class="ti ti-plus me-1"></i> Add Surgery
                        </button>
                    @endcan
                </div>
                <div class="table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Procedure</th>
                                <th>Date</th>
                                <th>Hospital</th>
                                <th>Surgeon</th>
                                <th>Notes</th>
                                @can('update', $patient)
                                    <th class="text-end pe-4">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->surgeries as $surgery)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $surgery->surgery_name }}</td>
                                    <td>{{ optional($surgery->surgery_date)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $surgery->hospital_name ?? '-' }}</td>
                                    <td>{{ $surgery->surgeon_name ?? '-' }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;"
                                            title="{{ $surgery->notes }}">
                                            {{ $surgery->notes ?? '-' }}
                                        </span>
                                    </td>
                                    @can('update', $patient)
                                        <td class="text-end pe-4">
                                            <form
                                                action="{{ route('patients.medical-history.surgery.destroy', $surgery) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this surgery record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light text-danger"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-tools-off fs-1 text-muted opacity-50 mb-2"></i>
                                            <p class="mb-0">No surgeries recorded.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Immunizations -->
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="ti ti-vaccine me-1"></i> Immunizations
                    </h5>
                    @can('update', $patient)
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal"
                            data-bs-target="#addImmunizationModal">
                            <i class="ti ti-plus me-1"></i> Add Immunization
                        </button>
                    @endcan
                </div>
                <div class="table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Vaccine</th>
                                <th>Date</th>
                                <th>Provider</th>
                                <th>Notes</th>
                                @can('update', $patient)
                                    <th class="text-end pe-4">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->immunizations as $immunization)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $immunization->vaccine_name }}</td>
                                    <td>{{ optional($immunization->immunization_date)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $immunization->provider_name ?? '-' }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;"
                                            title="{{ $immunization->notes }}">
                                            {{ $immunization->notes ?? '-' }}
                                        </span>
                                    </td>
                                    @can('update', $patient)
                                        <td class="text-end pe-4">
                                            <form
                                                action="{{ route('patients.medical-history.immunization.destroy', $immunization) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this immunization record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light text-danger"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-shield-off fs-1 text-muted opacity-50 mb-2"></i>
                                            <p class="mb-0">No immunizations recorded.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('update', $patient)
        <!-- Add Condition Modal -->
        <div class="modal fade" id="addConditionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Medical Condition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('patients.medical-history.condition.store', $patient) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Condition Name <span class="text-danger">*</span></label>
                                <input type="text" name="condition_name" class="form-control" required
                                    placeholder="e.g. Hypertension, Type 2 Diabetes">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Diagnosed Date</label>
                                    <input type="date" name="diagnosed_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="chronic">Chronic</option>
                                        <option value="treated">Treated</option>
                                        <option value="cured">Cured</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Diagnosed By</label>
                                <input type="text" name="doctor_name" class="form-control" placeholder="Doctor Name"
                                    value="{{ auth()->user()->hasRole('Doctor') ? auth()->user()->name : '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Condition</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Allergy Modal -->
        <div class="modal fade" id="addAllergyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Allergy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('patients.medical-history.allergy.store', $patient) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Allergy Name <span class="text-danger">*</span></label>
                                <input type="text" name="allergy_name" class="form-control" required
                                    placeholder="e.g. Penicillin, Peanuts">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Severity</label>
                                <select name="severity" class="form-select">
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Severe">Severe</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Reaction details..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Allergy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Surgery Modal -->
        <div class="modal fade" id="addSurgeryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Surgery Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('patients.medical-history.surgery.store', $patient) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Procedure Name <span class="text-danger">*</span></label>
                                <input type="text" name="surgery_name" class="form-control" required
                                    placeholder="e.g. Appendectomy">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="surgery_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hospital</label>
                                    <input type="text" name="hospital_name" class="form-control"
                                        placeholder="Hospital Name">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Surgeon</label>
                                <input type="text" name="surgeon_name" class="form-control"
                                    placeholder="Surgeon Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Surgery</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Immunization Modal -->
        <div class="modal fade" id="addImmunizationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Immunization Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('patients.medical-history.immunization.store', $patient) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Vaccine Name <span class="text-danger">*</span></label>
                                <input type="text" name="vaccine_name" class="form-control" required
                                    placeholder="e.g. Influenza, COVID-19">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="immunization_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provider/Clinic</label>
                                    <input type="text" name="provider_name" class="form-control"
                                        placeholder="Provider Name">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Immunization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</x-app-layout>
