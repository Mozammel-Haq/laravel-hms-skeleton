<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">OPD/IPD Summary</h3>
            <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary">Financial</a>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Patients</div>
                        <div class="display-6">{{ $patientsTotal }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Admissions Today</div>
                        <div class="display-6">{{ $admissionsToday }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Invoices</div>
                        <div class="display-6">{{ $invoicesTotal }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Payments</div>
                        <div class="display-6">{{ number_format($paymentsTotal, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="fw-semibold mb-2">Recent Admissions</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissions as $admission)
                                <tr>
                                    <td>{{ optional($admission->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $admission->created_at->format('Y-m-d H:i') }}</td>
                                    <td><span class="badge bg-success">{{ $admission->status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
