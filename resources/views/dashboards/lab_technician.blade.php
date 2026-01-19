<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-primary rounded-circle me-2"><i class="ti ti-test-pipe"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Pending Orders</p>
                                <h4 class="mb-0">{{ $cards['orders_pending'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-primary">Lab</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-secondary rounded-circle me-2"><i class="ti ti-check"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Completed Orders</p>
                                <h4 class="mb-0">{{ $cards['orders_completed'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Results</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Recent Lab Orders</h5>
                <a href="{{ route('lab.index') }}" class="btn btn-sm btn-outline-primary">Manage Lab</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td>
                                        @if($o->patient)
                                            <a href="{{ route('patients.show', $o->patient) }}" class="text-decoration-none text-body">
                                                {{ $o->patient->name }}
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td>{{ $o->created_at?->format('d M, H:i') }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $o->status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
