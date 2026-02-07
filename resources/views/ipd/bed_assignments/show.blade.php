<x-app-layout>
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-3 rounded-top">
                <div>
                    <h4 class="fw-bold text-primary mb-2">Bed Assignment Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}" class="text-decoration-none">IPD</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ipd.bed_assignments.index') }}" class="text-decoration-none">Bed Assignments</a></li>
                            <li class="breadcrumb-item active" aria-current="page">#{{ $assignment->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-{{ $assignment->released_at ? 'secondary' : 'success' }} fs-6">
                        {{ $assignment->released_at ? 'Released' : 'Active' }}
                    </span>
                    <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
<hr>
                <div class="row g-4">
                    <!-- Bed Info -->
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border border-secondary-subtle h-100">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Bed Details</h6>
                            <div class="mb-2">
                                <span class="text-muted d-block small">Bed Number</span>
                                <span class="fw-bold fs-5 text-dark">{{ optional($assignment->bed)->bed_number ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block small">Type</span>
                                <span class="fw-medium text-dark">{{ optional($assignment->bed->type)->name ?? 'Standard' }}</span>
                            </div>
                            <div>
                                <span class="text-muted d-block small">Ward/Floor</span>
                                <span class="fw-medium text-dark">{{ optional($assignment->bed->room)->floor ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info -->
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border border-secondary-subtle h-100">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Patient Details</h6>
                            <div class="mb-2">
                                <span class="text-muted d-block small">Patient Name</span>
                                <a href="{{ route('patients.show', $assignment->admission->patient) }}" class="fw-bold fs-5 text-decoration-none">
                                    {{ optional($assignment->admission->patient)->name ?? 'Unknown' }}
                                </a>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block small">Admission ID</span>
                                <a href="{{ route('ipd.show', $assignment->admission) }}" class="fw-medium text-decoration-none">
                                    #{{ $assignment->admission->id }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div class="col-12">
                        <hr class="border-secondary-subtle my-2">
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-subtle text-success rounded-circle p-2 me-3">
                                <i class="ti ti-calendar-plus fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Assigned At</span>
                                <span class="fw-bold text-dark">
                                    {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y, h:i A') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary-subtle text-gray rounded-circle p-2 me-3">
                                <i class="ti ti-calendar-minus fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Released At</span>
                                <span class="fw-bold text-dark">
                                    {{ $assignment->released_at ? \Carbon\Carbon::parse($assignment->released_at)->format('d M Y, h:i A') : 'Currently Occupied' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
