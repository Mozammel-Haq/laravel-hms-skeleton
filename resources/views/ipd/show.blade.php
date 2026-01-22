<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h4 class="mb-1">Admission Details</h4>
                <p class="text-muted mb-0">Patient admission record and status</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to IPD
                </a>
                @if ($admission->status === 'admitted')
                    <a href="{{ route('ipd.assign-bed', $admission) }}" class="btn btn-success">
                        <i class="ti ti-bed me-1"></i> Assign/Transfer Bed
                    </a>

                    @if (auth()->user()->hasRole('Doctor'))
                        @if (!$admission->discharge_recommended)
                            <form action="{{ route('ipd.recommend-discharge', $admission) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="ti ti-check me-1"></i> Recommend Discharge
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="ti ti-check me-1"></i> Discharge Recommended
                            </button>
                        @endif
                    @endif

                    @if (auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin']))
                        <a href="{{ route('ipd.discharge', $admission) }}" class="btn btn-danger">
                            <i class="ti ti-door-exit me-1"></i> Discharge
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Patient & Admission Info -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Patient Information</h5>
                        <a href="{{ route('patients.show', $admission->patient) }}"
                            class="d-flex align-items-center mb-3 text-decoration-none text-body">
                            <div class="avatar avatar-lg me-3">
                                @if ($admission->patient->profile_photo)
                                    <img src="{{ asset($admission->patient->profile_photo) }}"
                                        alt="{{ $admission->patient->name }}"
                                        class="rounded-circle w-100 h-100 object-fit-cover">
                                @else
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-3">
                                        {{ substr($admission->patient->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $admission->patient->name }}</h6>
                                <div class="text-muted small">{{ $admission->patient->patient_code }}</div>
                            </div>
                        </a>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Gender</span>
                                <span class="fw-semibold">{{ ucfirst($admission->patient->gender) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Phone</span>
                                <span class="fw-semibold">{{ $admission->patient->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Blood Group</span>
                                <span class="fw-semibold">{{ $admission->patient->blood_group ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Admission Info</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Status</span>
                                <span
                                    class="badge bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($admission->status) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Admission Date</span>
                                <span class="fw-semibold">{{ $admission->admission_date }}</span>
                            </li>
                            @if ($admission->discharge_date)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Discharge Date</span>
                                    <span class="fw-semibold">{{ $admission->discharge_date }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Attending Doctor</span>
                                <span class="fw-semibold">Dr.
                                    {{ $admission->doctor?->user?->name ?? 'Deleted Doctor' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Current Bed Status -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Bed Assignment</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $currentAssignment = $admission->bedAssignments->whereNull('released_at')->last();
                        @endphp

                        @if ($currentAssignment)
                            <div class="alert alert-success d-flex align-items-center mb-0">
                                <i class="ti ti-bed fs-2 me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Assigned to Bed:
                                        {{ $currentAssignment->bed->bed_number }}</h5>
                                    <p class="mb-0">
                                        Room: {{ $currentAssignment->bed->room->room_number }}
                                        ({{ $currentAssignment->bed->room->room_type }})<br>
                                        Ward: {{ $currentAssignment->bed->room->ward->name }}
                                    </p>
                                </div>
                            </div>
                        @else
                            @if ($admission->status === 'admitted')
                                <div class="alert alert-warning d-flex align-items-center mb-0">
                                    <i class="ti ti-alert-circle fs-2 me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">No Bed Assigned</h5>
                                        <p class="mb-0">This patient is currently admitted but not assigned to a bed.
                                            <a href="{{ route('ipd.assign-bed', $admission) }}"
                                                class="alert-link">Assign a bed now</a>.
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-secondary mb-0">
                                    Patient discharged.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Admission Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $admission->admission_reason ?? 'No notes provided.' }}</p>
                    </div>
                </div>

                <!-- Bed History -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Bed History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bed / Room / Ward</th>
                                        <th>Assigned At</th>
                                        <th>Released At</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admission->bedAssignments->sortByDesc('assigned_at') as $assignment)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">Bed {{ $assignment->bed->bed_number }}</div>
                                                <div class="small text-muted">{{ $assignment->bed->room->room_number }}
                                                    - {{ $assignment->bed->room->ward->name }}</div>
                                            </td>
                                            <td>{{ $assignment->assigned_at }}</td>
                                            <td>
                                                @if ($assignment->released_at)
                                                    {{ $assignment->released_at }}
                                                @else
                                                    <span class="badge bg-success-subtle text-success">Current</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($assignment->released_at)
                                                    {{ $assignment->assigned_at->diffForHumans($assignment->released_at, true) }}
                                                @else
                                                    {{ $assignment->assigned_at->diffForHumans(null, true) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No bed assignments
                                                recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Doctor Rounds -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Doctor Rounds</h5>
                        @if ($admission->status === 'admitted')
                            <a href="{{ route('ipd.rounds.create', $admission->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-plus me-1"></i> Add Round
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Doctor</th>
                                        <th>Notes</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admission->rounds as $round)
                                        <tr>
                                            <td>{{ $round->round_date }}</td>
                                            <td>{{ $round->doctor?->user?->name ?? 'Unknown' }}</td>
                                            <td>{{ $round->notes }}</td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light btn-icon" type="button" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('vitals.record', ['admission_id' => $admission->id, 'inpatient_round_id' => $round->id]) }}">
                                                                <i class="ti ti-heart-rate-monitor me-1"></i> Record Vitals
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No rounds recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Vitals -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Vitals</h5>
                        @if ($admission->status === 'admitted')
                            <a href="{{ route('vitals.record', ['admission_id' => $admission->id]) }}"
                                class="btn btn-sm btn-outline-success">
                                <i class="ti ti-heart-rate-monitor me-1"></i> Record Vitals
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Temperature</th>
                                        <th>Pulse</th>
                                        <th>BP</th>
                                        <th>Resp Rate</th>
                                        <th>Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admission->vitals as $vital)
                                        <tr>
                                            <td>{{ $vital->recorded_at?->format('d M Y H:i') }}</td>
                                            <td>{{ $vital->temperature }}</td>
                                            <td>{{ $vital->heart_rate }}</td>
                                            <td>{{ $vital->blood_pressure }}</td>
                                            <td>{{ $vital->respiratory_rate }}</td>
                                            <td>{{ $vital->recorder?->name ?? 'Staff' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No vitals recorded.</td>
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
</x-app-layout>
