<x-app-layout>
    <div class="container-fluid mx-2">


        <div class="row g-3">
            <!-- Left Summary -->
            <div class="col-lg-4">
                <div class="card mt-2">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="page-title mb-0">Doctor Profile</h4>
                            <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-primary">Back</a>
                        </div>
                        <hr>
                        <!-- Identity -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle border flex-shrink-0"
                                style="width:64px;height:64px;overflow:hidden;">
                                <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    class="w-100 h-100" style="object-fit:cover;">
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    {{ optional($doctor->user)->name ?? 'Doctor' }}
                                </div>
                                <div class="text-muted fs-13">
                                    {{ optional($doctor->department)->name ?? 'Department' }}
                                </div>

                                <!-- Contact (secondary) -->
                                <div class="d-flex flex-wrap mt-1 fs-13 text-muted">
                                    @if (optional($doctor->user)->phone)
                                        <span>
                                            <i class="ti ti-phone me-1"></i>
                                            {{ optional($doctor->user)->phone }}
                                        </span>
                                    @endif
                                    @if (optional($doctor->user)->email)
                                        <span>
                                            <i class="ti ti-mail me-1"></i>
                                            {{ optional($doctor->user)->email }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Key Facts -->
                        <div class="row g-3 fs-13 mb-3">
                            <div class="col-6">
                                <div class="text-muted">Specialization</div>
                                <div class="fw-semibold">{{ $doctor->specialization ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">License No</div>
                                <div class="fw-semibold">{{ $doctor->license_number ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Gender</div>
                                <div class="fw-semibold">{{ ucfirst($doctor->gender ?? 'N/A') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Blood Group</div>
                                <div class="fw-semibold">{{ $doctor->blood_group ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Experience</div>
                                <div class="fw-semibold">{{ $doctor->experience_years ?? 0 }} yrs</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Status</div>
                                @php
                                    $status = $doctor->status ?? 'inactive';
                                    $color = match($status) {
                                        'active' => 'success',
                                        'inactive' => 'warning',
                                        default => 'primary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Fees -->
                        <div class="border-top pt-2 mb-2 fs-13">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Consultation Fee</span>
                                <span class="fw-semibold">
                                    {{ $doctor->consultation_fee !== null ? number_format($doctor->consultation_fee, 2) : 'N/A' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Follow-up Fee</span>
                                <span class="fw-semibold">
                                    {{ $doctor->follow_up_fee !== null ? number_format($doctor->follow_up_fee, 2) : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="border-top pt-2">
                            <div class="text-muted fs-13 mb-1">Biography</div>
                            <div class="fs-13">
                                {{ $doctor->biography ?: 'No biography available.' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Right Content -->
            <div class="col-lg-8">

                <!-- Schedule -->
                <div class="card shadow-sm mb-3 mt-2">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Schedules</span>
                        <a href="{{ route('doctors.schedule', $doctor) }}"
                            class="btn btn-sm btn-outline-primary">Manage</a>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 datatable">
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
                                                @if ($s->schedule_date)
                                                    {{ \Carbon\Carbon::parse($s->schedule_date)->format('d M Y') }}
                                                @elseif(!is_null($s->day_of_week))
                                                    {{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$s->day_of_week] ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $s->start_time ?? 'N/A' }}</td>
                                            <td>{{ $s->end_time ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted fs-13">
                                                No schedules configured
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Education / Awards / Certifications -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2">Education</h6>
                                @forelse($doctor->educations as $e)
                                    <div class="mb-2 fs-13">
                                        <div class="fw-semibold">{{ $e->degree }}</div>
                                        <div class="text-muted">
                                            {{ $e->institution }}
                                            · {{ $e->start_year ?? '?' }}–{{ $e->end_year ?? 'Present' }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted fs-13">No records</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2">Awards</h6>
                                @forelse($doctor->awards as $a)
                                    <div class="fs-13 mb-2">
                                        <span class="fw-semibold">{{ $a->title }}</span>
                                        @if ($a->year)
                                            ({{ $a->year }})
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-muted fs-13">No awards</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2">Certifications</h6>
                                @forelse($doctor->certifications as $c)
                                    <div class="fs-13 mb-2">
                                        <div class="fw-semibold">{{ $c->title }}</div>
                                        <div class="text-muted">
                                            {{ $c->issued_by ?? '' }}
                                            @if ($c->issued_date)
                                                · {{ \Carbon\Carbon::parse($c->issued_date)->format('Y') }}
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted fs-13">No certifications</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
