<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card p-3 mb-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
        <div>
            <h5 class="fw-bold mb-1 text-primary">Admission Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-dots mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admission #{{ $admission->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
            @if ($admission->status === 'admitted')
                <a href="{{ route('ipd.assign-bed', $admission) }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-bed me-1"></i> Transfer Bed
                </a>

                @if (auth()->user()->hasRole('Doctor'))
                    @if (!$admission->discharge_recommended)
                        <form action="{{ route('ipd.recommend-discharge', $admission) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning text-white">
                                <i class="ti ti-check me-1"></i> Recommend Discharge
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="ti ti-check me-1"></i> Discharge Recommended
                        </button>
                    @endif
                @endif

                @if (auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin']))
                    <a href="{{ route('ipd.discharge', $admission) }}" class="btn btn-sm btn-danger">
                        <i class="ti ti-door-exit me-1"></i> Discharge
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>
    <div class="row g-4 mt-0">
            <!-- Left Column: Patient Profile -->
            <div class="col-lg-3">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            @if ($admission->patient && $admission->patient->profile_photo)
                                <img src="{{ asset($admission->patient->profile_photo) }}"
                                    alt="{{ $admission->patient->name }}"
                                    class="rounded-circle w-100 h-100 object-fit-cover border">
                            @else
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                                    {{ $admission->patient ? substr($admission->patient->name, 0, 1) : '?' }}
                                </span>
                            @endif
                        </div>
                        @if($admission->patient)
                            <h5 class="mb-1 fw-bold text-dark">{{ $admission->patient->name }}</h5>
                            <p class="text-muted small mb-3">{{ $admission->patient->patient_code }}</p>
                            <a href="{{ route('patients.show', $admission->patient) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Profile
                            </a>
                        @else
                            <h5 class="mb-1 fw-bold text-dark">Unknown Patient</h5>
                            <p class="text-muted small mb-3">N/A</p>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Gender</span>
                                <span class="fw-medium text-dark">{{ $admission->patient ? ucfirst($admission->patient->gender) : 'N/A' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Phone</span>
                                <span class="fw-medium text-dark">{{ $admission->patient->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span class="text-muted small text-uppercase fw-bold">Blood</span>
                                <span class="fw-medium text-dark">{{ $admission->patient->blood_group ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-2">
                        <h6 class="mb-0 fw-bold text-dark">Current Status</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted">Admission Status</span>
                            <span class="badge rounded-pill bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }} px-3 py-2">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </div>

                        @php
                            $currentAssignment = $admission->bedAssignments->whereNull('released_at')->last();
                        @endphp

                        @if ($currentAssignment)
                            <div class="p-3 bg-light rounded border border-light text-center mt-3">
                                <div class="text-muted small text-uppercase fw-bold mb-1">Assigned Bed</div>
                                <h4 class="mb-0 fw-bold text-primary">{{ $currentAssignment->bed->bed_number }}</h4>
                                <div class="small text-muted mt-1">
                                    {{ $currentAssignment->bed->room->room_number }} • {{ $currentAssignment->bed->room->ward->name }}
                                </div>
                            </div>
                        @else
                            @if ($admission->status === 'admitted')
                                <div class="alert alert-warning mb-0 border-0 d-flex align-items-center small">
                                    <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                    <div>No bed assigned.</div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="col-lg-9">
                <!-- Admission Info Grid -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold text-dark">Admission Information</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6 p-4 border-bottom border-end">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Attending Doctor</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2 rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                        <i class="ti ti-stethoscope fs-6"></i>
                                    </div>
                                    <span class="fw-medium text-dark">Dr. {{ $admission->doctor?->user?->name ?? 'Deleted Doctor' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 p-4 border-bottom">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Admission Date</label>
                                <span class="fw-medium text-dark fs-6">{{ $admission->admission_date }}</span>
                            </div>
                            <div class="col-md-6 p-4 border-end">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Discharge Date</label>
                                <span class="fw-medium text-dark">{{ $admission->discharge_date ?? 'Not Discharged' }}</span>
                            </div>
                            <div class="col-md-6 p-4">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Reason for Admission</label>
                                <span class="fw-medium text-dark">{{ $admission->admission_reason ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bed History Table -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">Bed Assignment History</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Bed / Location</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Assigned</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Released</th>
                                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted border-bottom">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admission->bedAssignments->sortByDesc('assigned_at') as $assignment)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-dark">{{ $assignment->bed->bed_number }}</div>
                                            <div class="small text-muted">{{ $assignment->bed->room->room_number }} • {{ $assignment->bed->room->ward->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-secondary">{{ $assignment->assigned_at->format('M d, Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            @if ($assignment->released_at)
                                                <span class="text-secondary">{{ $assignment->released_at->format('M d, Y H:i') }}</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Current</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-secondary">
                                            @if ($assignment->released_at)
                                                {{ $assignment->assigned_at->diffForHumans($assignment->released_at, true) }}
                                            @else
                                                {{ $assignment->assigned_at->diffForHumans(null, true) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No bed assignments recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rounds & Vitals Split -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-dark">Doctor Rounds</h6>
                                @if ($admission->status === 'admitted')
                                    <a href="{{ route('ipd.rounds.create', $admission->id) }}" class="btn btn-sm btn-light border">
                                        <i class="ti ti-plus"></i> Add
                                    </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Date</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Doctor</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admission->rounds as $round)
                                            <tr>
                                                <td class="px-3 py-2 small">{{ $round->round_date }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $round->doctor?->user?->name ?? 'Unknown' }}</td>
                                                <td class="px-3 py-2 text-end">
                                                    <a href="{{ route('vitals.record', ['admission_id' => $admission->id, 'inpatient_round_id' => $round->id]) }}"
                                                       class="btn btn-sm btn-icon btn-ghost-primary"
                                                       title="Record Vitals">
                                                        <i class="ti ti-heart-rate-monitor"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted small">No rounds recorded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-dark">Recent Vitals</h6>
                                @if ($admission->status === 'admitted')
                                    <a href="{{ route('vitals.record', ['admission_id' => $admission->id]) }}" class="btn btn-sm btn-light border">
                                        <i class="ti ti-plus"></i> Add
                                    </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Time</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">BP</th>
                                            <th class="px-3 py-2 text-uppercase small fw-bold text-muted">Temp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admission->vitals->take(5) as $vital)
                                            <tr>
                                                <td class="px-3 py-2 small">{{ $vital->recorded_at?->format('H:i d/m') }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $vital->blood_pressure }}</td>
                                                <td class="px-3 py-2 small fw-medium">{{ $vital->temperature }}°</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted small">No vitals recorded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
</x-app-layout>
