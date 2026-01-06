<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-primary rounded-circle me-2"><i class="ti ti-calendar"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Appointments Today</p>
                                <h4 class="mb-0">{{ $cards['appointments_today'] }}</h4>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookModal">Book</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-secondary rounded-circle me-2"><i class="ti ti-users"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Total Patients</p>
                                <h4 class="mb-0">{{ $cards['patients_total'] }}</h4>
                            </div>
                        </div>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm">Manage</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Recent Appointments</h5>
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $a)
                                        <tr>
                                            <td>{{ $a->patient->name ?? 'Patient' }}</td>
                                            <td>{{ $a->doctor->user->name ?? 'Doctor' }}</td>
                                            <td>{{ $a->created_at?->format('d M, H:i') }}</td>
                                            <td><span class="badge bg-light text-dark">{{ $a->status ?? 'pending' }}</span></td>
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
                        <h5 class="mb-0">New Patients</h5>
                        <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Registered</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->created_at?->format('d M') }}</td>
                                            <td><span class="badge bg-secondary">{{ $p->status ?? 'active' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('appointments.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Patient</label>
                                <select class="form-select select2" name="patient_id"></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Doctor</label>
                                <select class="form-select select2" name="doctor_id"></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="appointment_date">
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
