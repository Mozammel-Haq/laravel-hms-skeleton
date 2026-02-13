<x-app-layout>
    <div class="container-fluid py-2 px-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                            <!-- Header -->
            <div class="row align-items-center bg-primary-subtle text-primary p-3 m-2 rounded-top">
                <div class="col ">
                    <h4 class="mb-1 fw-bold text-dark">Doctor Profile</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('doctors.index') }}" class="text-decoration-none">Doctors</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ optional($doctor->user)->name ?? 'Doctor' }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-auto d-flex gap-2">
                    <a href="{{ route('doctors.schedule', $doctor) }}" class="btn btn-primary">
                        <i class="ti ti-calendar-time me-1"></i> Manage Schedule
                    </a>
                </div>
            </div>
            <hr>
                <div class="row px-2 mt-2">
            <!-- Left Column: Profile & Info -->
            <div class="col-lg-3">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle border border-3 border-white shadow-sm overflow-hidden" style="width: 120px; height: 120px;">
                                <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    alt="{{ optional($doctor->user)->name }}"
                                    class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-{{ $doctor->status === 'active' ? 'success' : 'secondary' }} border border-2 border-white">
                                {{ ucfirst($doctor->status ?? 'inactive') }}
                            </span>
                        </div>

                        <h5 class="mb-1 fw-bold text-dark">{{ optional($doctor->user)->name ?? 'Doctor' }}</h5>
                        <p class="text-primary fw-medium mb-3">
                            {{ optional($doctor->department)->name ?? 'Department' }}
                        </p>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            @if (optional($doctor->user)->phone)
                                <a href="tel:{{ optional($doctor->user)->phone }}" class="btn btn-outline-primary btn-sm rounded-circle"
                                    data-bs-toggle="tooltip" title="Call">
                                    <i class="ti ti-phone"></i>
                                </a>
                            @endif
                            @if (optional($doctor->user)->email)
                                <a href="mailto:{{ optional($doctor->user)->email }}" class="btn btn-outline-info btn-sm rounded-circle"
                                    data-bs-toggle="tooltip" title="Email">
                                    <i class="ti ti-mail"></i>
                                </a>
                            @endif
                        </div>

                        <hr class="my-3 border-secondary-subtle">

                        <div class="text-start">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Specializations</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $specData = $doctor->specialization;
                                        $specData = \Illuminate\Support\Arr::wrap($specData);
                                        $finalSpecs = [];
                                        foreach ($specData as $item) {
                                            if (is_string($item)) {
                                                $decoded = json_decode($item, true);
                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                    if (is_array($decoded)) {
                                                        foreach (\Illuminate\Support\Arr::flatten($decoded) as $sub) {
                                                            $finalSpecs[] = $sub;
                                                        }
                                                    } else {
                                                        $finalSpecs[] = $decoded;
                                                    }
                                                } else {
                                                    $finalSpecs[] = $item;
                                                }
                                            } else {
                                                $finalSpecs[] = $item;
                                            }
                                        }
                                        $pieces = [];
                                        foreach (\Illuminate\Support\Arr::flatten($finalSpecs) as $s) {
                                            if (is_string($s)) {
                                                foreach (explode(',', $s) as $part) {
                                                    $t = trim($part, " \t\n\r\0\x0B\"'[]");
                                                    if ($t !== '') {
                                                        $pieces[] = $t;
                                                    }
                                                }
                                            }
                                        }
                                        // $pieces = array_slice($pieces, 0, 5); // Show all or limit?
                                    @endphp
                                    @if (count($pieces) > 0)
                                        @foreach ($pieces as $spec)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ $spec }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold mb-1">Experience</label>
                                    <div class="fw-semibold text-dark">{{ $doctor->experience_years ?? 0 }} Years</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold mb-1">License</label>
                                    <div class="fw-semibold text-dark">{{ $doctor->license_number ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold mb-1">Gender</label>
                                    <div class="fw-semibold text-dark text-capitalize">{{ $doctor->gender ?? '-' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small text-uppercase fw-bold mb-1">Blood Group</label>
                                    <div class="fw-semibold text-dark">{{ $doctor->blood_group ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="bg-light rounded p-3 mb-3 border">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Location</label>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Room</span>
                                    <span class="fw-bold text-dark">{{ $doctor->consultation_room_number ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Floor</span>
                                    <span class="fw-bold text-dark">{{ $doctor->consultation_floor ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="bg-primary-subtle rounded p-3 border border-primary-subtle">
                                <h6 class="mb-3 text-primary fw-bold">
                                    <i class="ti ti-coin me-1"></i> Consultation Fees
                                </h6>
                                <div class="d-flex justify-content-between mb-2 border-bottom border-primary-subtle pb-2">
                                    <span class="text-dark">First Visit</span>
                                    <span class="fw-bold text-primary">
                                        {{ $doctor->consultation_fee !== null ? 'TK ' . number_format($doctor->consultation_fee, 2) : 'N/A' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark">Follow-up</span>
                                    <span class="fw-bold text-primary">
                                        {{ $doctor->follow_up_fee !== null ? 'TK ' . number_format($doctor->follow_up_fee, 2) : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Biography, Schedule, Qualifications -->
            <div class="col-lg-9">
                <!-- Biography -->
                @if ($doctor->biography)
                    <div class="card border border-secondary-subtle shadow-sm mb-4">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0 fw-bold text-dark">Biography</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-secondary">{{ $doctor->biography }}</p>
                        </div>
                    </div>
                @endif

                <!-- Schedule -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-clock me-2 text-primary"></i> Schedules
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Day / Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($doctor->schedules as $s)
                                        <tr>
                                            <td class="ps-4 fw-medium">
                                                @if ($s->schedule_date)
                                                    <span class="text-primary">{{ $s->schedule_date->format('d M Y') }}</span>
                                                @elseif(!is_null($s->day_of_week))
                                                    {{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$s->day_of_week] ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $s->start_time ? $s->start_time->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ $s->end_time ? $s->end_time->format('h:i A') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No schedules configured.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Qualifications Grid -->
                <div class="row g-4">
                    <!-- Education -->
                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm h-100">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="card-title mb-0 fw-bold text-dark">
                                    <i class="ti ti-school me-2 text-info"></i> Education
                                </h6>
                            </div>
                            <div class="card-body">
                                @forelse($doctor->educations as $e)
                                    <div class="mb-3 last-mb-0">
                                        <div class="fw-bold text-dark">{{ $e->degree }}</div>
                                        <div class="text-muted small">
                                            {{ $e->institution }}
                                            <span class="mx-1">•</span>
                                            {{ $e->start_year ?? '?' }} – {{ $e->end_year ?? 'Present' }}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">No education records found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Awards & Certifications -->
                    <div class="col-md-6">
                        <div class="card border border-secondary-subtle shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="card-title mb-0 fw-bold text-dark">
                                    <i class="ti ti-trophy me-2 text-warning"></i> Awards
                                </h6>
                            </div>
                            <div class="card-body">
                                @forelse($doctor->awards as $a)
                                    <div class="mb-3 last-mb-0">
                                        <div class="fw-bold text-dark">{{ $a->title }}</div>
                                        @if ($a->year)
                                            <div class="text-muted small">{{ $a->year }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">No awards found.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="card border border-secondary-subtle shadow-sm">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="card-title mb-0 fw-bold text-dark">
                                    <i class="ti ti-certificate me-2 text-success"></i> Certifications
                                </h6>
                            </div>
                            <div class="card-body">
                                @forelse($doctor->certifications as $c)
                                    <div class="mb-3 last-mb-0">
                                        <div class="fw-bold text-dark">{{ $c->title }}</div>
                                        <div class="text-muted small">
                                            {{ $c->issued_by ?? '' }}
                                            @if ($c->issued_date)
                                                <span class="mx-1">•</span>
                                                {{ $c->issued_date->format('Y') }}
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">No certifications found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
