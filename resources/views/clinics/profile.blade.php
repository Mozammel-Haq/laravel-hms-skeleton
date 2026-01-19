<x-app-layout>
    @php
        $clinic = optional(auth()->user())->clinic;
    @endphp
    <div class="container-fluid">

<div class="row g-4">

    {{-- CLINIC PROFILE --}}
    <div class="col-lg-8">
        <div class="card mt-2 mx-2">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Clinic Profile</h3>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary">
                        Dashboard
                    </a>
                </div>
                <hr>
                <hr class="my-4">

                {{-- Profile Grid --}}
                <div class="row g-4">

                    {{-- Identity --}}
                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">Clinic Name</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->name ?? 'Clinic' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">Clinic Code</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->code ?? '—' }}
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">City</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->city ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">Country</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->country ?? '—' }}
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">Timezone</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->timezone ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-2 p-3 h-100">
                            <div class="text-muted small mb-1">Currency</div>
                            <div class="fw-semibold">
                                {{ optional($clinic)->currency ?? '—' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-lg-4">
        <div class="card mt-2">
            <div class="card-body">

                <div class="fw-semibold mb-3">Quick Actions</div>

                <div class="d-grid gap-2">
                    <a href="{{ route('departments.index') }}"
                       class="btn btn-outline-primary">
                        Manage Departments
                    </a>

                    <a href="{{ route('staff.index') }}"
                       class="btn btn-outline-primary">
                        Manage Staff
                    </a>

                    <a href="{{ route('doctors.index') }}"
                       class="btn btn-outline-primary">
                        Manage Doctors
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

    </div>
</x-app-layout>
