<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-secondary rounded-circle me-2"><i
                                    class="ti ti-prescription"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Active Prescriptions</p>
                                <h4 class="mb-0">{{ $cards['prescriptions_active'] }}</h4>
                            </div>
                        </div>
                        <a href="{{ route('clinical.prescriptions.index') }}" class="btn btn-sm btn-secondary">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-primary rounded-circle me-2"><i
                                    class="ti ti-shopping-cart"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Sales Today</p>
                                <h4 class="mb-0">{{ number_format($cards['sales_today'], 2) }}</h4>
                            </div>
                        </div>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-sm btn-primary">POS</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Recent Prescriptions</h5>
                        <a href="{{ route('clinical.prescriptions.index') }}"
                            class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Issued</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prescriptions as $p)
                                        <tr>
                                            <td>{{ $p->patient->name ?? 'Patient' }}</td>
                                            <td>{{ $p->created_at }}</td>
                                            <td><span class="badge bg-secondary">{{ $p->status ?? 'active' }}</span>
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
                        <h5 class="mb-0">Recent Sales</h5>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $s)
                                        <tr>
                                            <td>{{ $s->patient->name ?? 'Patient' }}</td>
                                            <td>{{ $s->created_at }}</td>
                                            <td>{{ number_format($s->total_amount, 2) }}</td>
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
