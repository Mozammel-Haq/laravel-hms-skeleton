<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Doctor Profile</h3>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-xl rounded-circle me-3">
                                <img src="{{ asset('assets') }}/img/doctors/doctor-01.jpg" alt="img" class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">{{ optional($doctor->user)->name ?? 'Doctor' }}</div>
                                <div class="text-muted">{{ optional($doctor->department)->name ?? 'Department' }}</div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-muted">Specialization</div>
                                <div class="fw-semibold">{{ $doctor->specialization ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">License</div>
                                <div class="fw-semibold">{{ $doctor->license_number ?? 'N/A' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted">Status</div>
                                <span class="badge bg-{{ ($doctor->status ?? 'inactive') === 'active' ? 'success' : 'secondary' }}">{{ $doctor->status ?? 'inactive' }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Experience</div>
                            <div class="fw-semibold">{{ $doctor->experience_years ?? 0 }} years</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Consultation Fee</div>
                            <div class="fw-semibold">
                                @if(!is_null($doctor->consultation_fee))
                                    {{ number_format($doctor->consultation_fee, 2) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Follow Up Fee</div>
                            <div class="fw-semibold">
                                @if(!is_null($doctor->follow_up_fee ?? null))
                                    {{ number_format($doctor->follow_up_fee, 2) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-muted mb-1">Biography</div>
                            <div class="fs-13">
                                {{ $doctor->biography ?: 'No biography available.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div class="fw-semibold">Schedules</div>
                        <a href="{{ route('doctors.schedule', $doctor) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Day / Date</th>
                                        <th>Start</th>
                                        <th>End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($doctor->schedules as $s)
                                        <tr>
                                            <td>{{ $s->id }}</td>
                                            <td>
                                                @if(!empty($s->schedule_date))
                                                    {{ \Carbon\Carbon::parse($s->schedule_date)->format('d M Y') }}
                                                @elseif(!is_null($s->day_of_week))
                                                    @php
                                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                                    @endphp
                                                    {{ $days[(int) $s->day_of_week] ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $s->start_time ?? 'N/A' }}</td>
                                            <td>{{ $s->end_time ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No schedules configured</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Education Information</h5>
                                @if($doctor->educations->isEmpty())
                                    <div class="text-muted fs-13">No education records available.</div>
                                @else
                                    <ul class="activity-feed rounded">
                                        @foreach($doctor->educations as $education)
                                            <li class="feed-item timeline-item mb-2">
                                                <h6 class="fw-bold mb-1">
                                                    {{ $education->institution }} - {{ $education->degree }}
                                                </h6>
                                                <p class="mb-0 fs-13">
                                                    @if($education->start_year || $education->end_year)
                                                        {{ $education->start_year ?? '?' }} - {{ $education->end_year ?? 'Present' }}
                                                    @else
                                                        Years not specified
                                                    @endif
                                                    @if($education->country)
                                                        · {{ $education->country }}
                                                    @endif
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 mb-3">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Awards &amp; Recognition</h5>
                                @if($doctor->awards->isEmpty())
                                    <div class="text-muted fs-13">No awards recorded.</div>
                                @else
                                    @foreach($doctor->awards as $award)
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="me-2"><i class="ti ti-award"></i></span>
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $award->title }}
                                                    @if($award->year)
                                                        ({{ $award->year }})
                                                    @endif
                                                </h6>
                                            </div>
                                            @if($award->description)
                                                <p class="mb-0 fs-13">
                                                    {{ $award->description }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Certifications</h5>
                                @if($doctor->certifications->isEmpty())
                                    <div class="text-muted fs-13">No certifications recorded.</div>
                                @else
                                    @foreach($doctor->certifications as $certification)
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="me-2"><i class="ti ti-award"></i></span>
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $certification->title }}
                                                </h6>
                                            </div>
                                            <p class="mb-0 fs-13">
                                                @if($certification->issued_by)
                                                    {{ $certification->issued_by }}
                                                @endif
                                                @if($certification->issued_date || $certification->expiry_date)
                                                    @if($certification->issued_by)
                                                        ·
                                                    @endif
                                                    @if($certification->issued_date)
                                                        Issued {{ \Carbon\Carbon::parse($certification->issued_date)->format('d M Y') }}
                                                    @endif
                                                    @if($certification->expiry_date)
                                                        , Expires {{ \Carbon\Carbon::parse($certification->expiry_date)->format('d M Y') }}
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
