<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="page-title mb-0">Doctor Dashboard</h3>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-primary rounded-circle me-2"><i class="ti ti-calendar"></i></span>
                                <div>
                                    <p class="mb-0 text-muted">Appointments Today</p>
                                    <h4 class="mb-0">{{ $cards['appointments_today'] }}</h4>
                                </div>
                            </div>
                            <span class="badge bg-primary">Today</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-secondary rounded-circle me-2"><i
                                        class="ti ti-prescription"></i></span>
                                <div>
                                    <p class="mb-0 text-muted">Prescriptions This Month</p>
                                    <h4 class="mb-0">{{ $cards['prescriptions_month'] }}</h4>
                                </div>
                            </div>
                            <span class="badge bg-secondary">This Month</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-info rounded-circle me-2"><i class="ti ti-test-pipe"></i></span>
                                <div>
                                    <p class="mb-0 text-muted">Pending Lab Orders</p>
                                    <h4 class="mb-0">{{ $cards['lab_orders_pending'] }}</h4>
                                </div>
                            </div>
                            <span class="badge bg-info">Lab</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Upcoming Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $a)
                                        <tr>
                                            <td>
                                                @if($a->patient)
                                                    <a href="{{ route('patients.show', $a->patient) }}" class="text-decoration-none text-body">
                                                        {{ $a->patient->name }}
                                                    </a>
                                                @else
                                                    Patient
                                                @endif
                                            </td>
                                            <td>{{ $a->created_at }}</td>
                                            <td><span
                                                    class="badge bg-light text-dark">{{ $a->status ?? 'pending' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('appointments.show', $a) }}"
                                                    class="btn btn-sm btn-primary">Open</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Recent Prescriptions</h5>
                        <a href="{{ route('clinical.prescriptions.index') }}"
                            class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Issued</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prescriptions as $p)
                                        <tr>
                                            <td>{{ $p->patient->name ?? 'Patient' }}</td>
                                            <td>{{ $p->created_at }}</td>
                                            <td><span class="badge bg-secondary">{{ $p->status ?? 'active' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('clinical.prescriptions.show', $p) }}"
                                                    class="btn btn-sm btn-secondary">Open</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
