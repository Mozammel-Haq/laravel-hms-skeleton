<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-primary rounded-circle me-2"><i class="ti ti-bed"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Active Admissions</p>
                                <h4 class="mb-0">{{ $cards['admissions_active'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-primary">IPD</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-secondary rounded-circle me-2"><i class="ti ti-building"></i></span>
                            <div>
                                <p class="mb-0 text-muted">Beds Available</p>
                                <h4 class="mb-0">{{ $cards['beds_available'] }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Beds</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Admitted Patients</h5>
                <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-primary">Manage IPD</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admission Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissions as $ad)
                                <tr>
                                    <td>{{ $ad->patient->name ?? 'Patient' }}</td>
                                    <td>{{ $ad->doctor->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $ad->created_at?->format('d M, H:i') }}</td>
                                    <td><span class="badge bg-primary">{{ $ad->status }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('ipd.show', $ad) }}" class="btn btn-sm btn-primary">Open</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
