<x-app-layout>
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row g-3 mb-4 py-3">
            <div class="col-md-3">
                <div class="card h-100 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fs-6 opacity-75">Doctors</div>
                            <i class="ti ti-stethoscope fs-4"></i>
                        </div>
                        <h2 class="mb-0">{{ $stats['doctors']['total'] }}</h2>
                        <small class="opacity-75">
                            +{{ $stats['doctors']['last_30_days'] }} this month
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fs-6 opacity-75">Patients</div>
                            <i class="ti ti-users fs-4"></i>
                        </div>
                        <h2 class="mb-0">{{ $stats['patients']['total'] }}</h2>
                        <small class="opacity-75">
                            +{{ $stats['patients']['last_7_days'] }} this week
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fs-6 opacity-75">Appointments</div>
                            <i class="ti ti-calendar fs-4"></i>
                        </div>
                        <h2 class="mb-0">{{ $stats['appointments']['total'] }}</h2>
                        <small class="opacity-75">
                            {{ $stats['appointments']['active_last_7_days'] }} active this week
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fs-6 opacity-75">Revenue (7d)</div>
                            <i class="ti ti-currency-dollar fs-4"></i>
                        </div>
                        <h2 class="mb-0">{{ number_format($stats['revenue']['last_7_days'], 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Latest Appointments -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Latest Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle datatable">
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
                                            <div class="fw-semibold">{{ $appointment->patient->name }}</div>
                                            <div class="small text-muted">{{ $appointment->patient->patient_code }}
                                            </div>
                                        </td>
                                        <td>{{ $appointment->doctor?->user?->name ?? 'Deleted Doctor' }}</td>
                                        <td>
                                            <div>{{ $appointment->appointment_date }}</div>
                                            <div class="small text-muted">{{ $appointment->start_time }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
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
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:32px;height:32px">
                                            {{ substr($doctor->user?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $doctor->user?->name ?? 'Deleted Doctor' }}
                                            </div>
                                            <div class="small text-muted">{{ $doctor->specialization }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ $doctor->appointments_count }}
                                        appts</span>
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
                <div class="card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 datatable">
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
</x-app-layout>
