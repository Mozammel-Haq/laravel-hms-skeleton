<x-app-layout>
    <div class="container-fluid mx-2">
<div class="row g-3 mb-2 py-3">

    {{-- Doctors --}}
    <div class="col-xl-3 col-md-6">
        <div class="position-relative card border rounded-2 shadow-sm h-100 overflow-hidden">
            <img src="{{ asset('assets/img/bg/bg-01.svg') }}"
                 class="position-absolute top-0 start-0 w-100 h-100 "
                 alt="bg">
            <div class="card-body position-relative d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-primary text-white rounded-circle">
                        <i class="ti ti-stethoscope fs-4"></i>
                    </span>
                    <span class="text-muted fw-medium">Doctors</span>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['doctors']['total'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Patients --}}
    <div class="col-xl-3 col-md-6">
        <div class="position-relative card border rounded-2 shadow-sm h-100 overflow-hidden">
            <img src="{{ asset('assets/img/bg/bg-02.svg') }}"
                 class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
                 alt="bg">
            <div class="card-body position-relative d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-success text-white rounded-circle">
                        <i class="ti ti-users fs-4"></i>
                    </span>
                    <span class="text-muted fw-medium">Patients</span>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['patients']['total'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Appointments --}}
    <div class="col-xl-3 col-md-6">
        <div class="position-relative card border rounded-2 shadow-sm h-100 overflow-hidden">
            <img src="{{ asset('assets/img/bg/bg-03.svg') }}"
                 class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
                 alt="bg">
            <div class="card-body position-relative d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-warning text-dark rounded-circle">
                        <i class="ti ti-calendar fs-4"></i>
                    </span>
                    <span class="text-muted fw-medium">Appointments</span>
                </div>
                <h3 class="fw-bold mb-0">{{ $stats['appointments']['total'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="col-xl-3 col-md-6">
        <div class="position-relative card border rounded-2 shadow-sm h-100 overflow-hidden">
            <img src="{{ asset('assets/img/bg/bg-04.svg') }}"
                 class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
                 alt="bg">
            <div class="card-body position-relative d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-info text-white rounded-circle">
                        <i class="ti ti-currency-dollar fs-4"></i>
                    </span>
                    <span class="text-muted fw-medium">Revenue</span>
                </div>
                <h3 class="fw-bold mb-0">
                    {{ number_format($stats['revenue']['last_7_days'], 2) }}
                </h3>
            </div>
        </div>
    </div>

</div>


        <div class="row g-4 mb-4">
            <!-- Latest Appointments -->
            <div class="col-md-8">
                <div class="card p-2">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Latest Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('patients.show', $appointment->patient) }}"
                                                class="text-decoration-none text-body">
                                                <div class="fw-semibold">{{ $appointment->patient->name }}</div>
                                                <div class="small text-muted">
                                                    {{ $appointment->patient->patient_code }}
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @if ($appointment->doctor)
                                                <a href="{{ route('doctors.show', $appointment->doctor) }}"
                                                    class="text-decoration-none text-body">
                                                    {{ $appointment->doctor->user?->name ?? 'Deleted Doctor' }}
                                                </a>
                                            @else
                                                Deleted Doctor
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                class="text-decoration-none text-body">
                                                <div>{{ $appointment->appointment_date }}</div>
                                                <div class="small text-muted">{{ $appointment->start_time }}</div>
                                            </a>
                                        </td>
                                        <td>
                                            @php
    $statusColors = [
        'completed' => 'success',
        'cancelled' => 'danger',
        'confirmed' => 'primary',
        'arrived'   => 'info',
        'pending'   => 'warning',
    ];
@endphp

<span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
    {{ ucfirst($appointment->status) }}
</span>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No recent appointments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Doctor Availability -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Doctor Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Active</span>
                            <span class="badge bg-success rounded-pill">{{ $doctorAvailability['active'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Inactive</span>
                            <span class="badge bg-secondary rounded-pill">{{ $doctorAvailability['inactive'] }}</span>
                        </div>
                        <hr>
                        <h6 class="mb-3">Popular Doctors</h6>
                        <ul class="list-group list-group-flush">
                            @foreach ($popularDoctors as $doctor)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                        class="d-flex align-items-center text-decoration-none text-body">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="{{ $doctor->user?->name ?? 'Doctor' }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $doctor->user?->name ?? 'Deleted Doctor' }}
                                            </div>
                                            <div class="small text-muted">{{ $doctor->specialization }}</div>
                                        </div>
                                    </a>
                                    <span class="badge bg-light text-dark">
                                        {{ $doctor->appointments_count }} appts
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Top Departments -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Top Departments</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($topDepartments as $dept)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">{{ $dept->name }}</span>
                                    <span>{{ $dept->total }} appointments</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $dept->total > 0 ? ($dept->total / $topDepartments->max('total')) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-md-6">
                <div class="card h-100 mb-2">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Patient</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $invoice)
                                    <tr>
                                        <td>#{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->patient->name }}</td>
                                        <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No recent transactions</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
