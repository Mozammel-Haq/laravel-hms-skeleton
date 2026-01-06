<x-app-layout>
    @php
        $clinic = optional(auth()->user())->clinic;
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Clinic Profile</h3>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted">Name</div>
                                <div class="fw-semibold">{{ optional($clinic)->name ?? 'Clinic' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">Code</div>
                                <div class="fw-semibold">{{ optional($clinic)->code ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">City</div>
                                <div class="fw-semibold">{{ optional($clinic)->city ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">Country</div>
                                <div class="fw-semibold">{{ optional($clinic)->country ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">Timezone</div>
                                <div class="fw-semibold">{{ optional($clinic)->timezone ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">Currency</div>
                                <div class="fw-semibold">{{ optional($clinic)->currency ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Quick Actions</div>
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-primary w-100 mb-2">Manage
                            Departments</a>
                        <a href="{{ route('staff.index') }}" class="btn btn-outline-primary w-100 mb-2">Manage Staff</a>
                        <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary w-100">Manage Doctors</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
