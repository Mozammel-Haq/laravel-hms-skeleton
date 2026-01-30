<x-app-layout>
    <div class="container-fluid mx-2">


        <div class="row g-3">
            <!-- Left Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mt-2">
                    <div class="card-body p-4">
                        <!-- Header Profile -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block mb-3">
                                <div class="rounded-circle border border-3 border-light shadow-sm"
                                    style="width:120px;height:120px;overflow:hidden;">
                                    <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                        class="w-100 h-100" style="object-fit:cover;">
                                </div>
                                <span
                                    class="position-absolute bottom-0 end-0 badge rounded-pill bg-{{ $doctor->status === 'active' ? 'success' : 'secondary' }} border border-2 border-white">
                                    {{ ucfirst($doctor->status ?? 'inactive') }}
                                </span>
                            </div>

                            <h4 class="mb-1">{{ optional($doctor->user)->name ?? 'Doctor' }}</h4>
                            <p class="text-primary fw-medium mb-2">
                                {{ optional($doctor->department)->name ?? 'Department' }}
                            </p>

                            <!-- Social/Contact Actions -->
                            <div class="d-flex justify-content-center gap-2 mb-4">
                                @if (optional($doctor->user)->phone)
                                    <a href="tel:{{ optional($doctor->user)->phone }}"
                                        class="btn btn-sm btn-outline-light text-dark" title="Call">
                                        <i class="ti ti-phone"></i>
                                    </a>
                                @endif
                                @if (optional($doctor->user)->email)
                                    <a href="mailto:{{ optional($doctor->user)->email }}"
                                        class="btn btn-sm btn-outline-light text-dark" title="Email">
                                        <i class="ti ti-mail"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="row g-3 mb-4">
                            <!-- Specializations -->
                            <div class="col-12">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Specializations</label>
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
                                        $pieces = array_slice($pieces, 0, 2);
                                    @endphp
                                    @if (count($pieces) > 0)
                                        @foreach ($pieces as $spec)
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ $spec }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Professional Info -->
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">Experience</label>
                                <div class="fw-semibold">{{ $doctor->experience_years ?? 0 }} Years</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">License</label>
                                <div class="fw-semibold">{{ $doctor->license_number ?? 'N/A' }}</div>
                            </div>

                            <!-- Personal Info -->
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">Gender</label>
                                <div>{{ ucfirst($doctor->gender ?? '-') }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">Blood Group</label>
                                <div>{{ $doctor->blood_group ?? '-' }}</div>
                            </div>

                            <!-- Location -->
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">Room</label>
                                <div>{{ $doctor->consultation_room_number ?? '-' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small text-uppercase fw-bold">Floor</label>
                                <div>{{ $doctor->consultation_floor ?? '-' }}</div>
                            </div>
                        </div>

                        <!-- Fees Card -->
                        <div class="bg-light rounded p-3">
                            <h6 class="mb-3 text-primary">Consultation Fees</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>First Visit</span>
                                <span class="fw-bold text-dark">
                                    {{ $doctor->consultation_fee !== null ? 'TK ' . number_format($doctor->consultation_fee, 2) : 'N/A' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Follow-up</span>
                                <span class="fw-bold text-dark">
                                    {{ $doctor->follow_up_fee !== null ? 'TK ' . number_format($doctor->follow_up_fee, 2) : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Right Content -->
            <div class="col-lg-8">

                <!-- Biography -->
                @if ($doctor->biography)
                    <div class="card border-0 shadow-sm mb-3 mt-2">
                        <div class="card-body p-4">
                            <h5 class="card-title text-primary mb-3">Biography</h5>
                            <p class="card-text text-grey">{{ $doctor->biography }}</p>
                        </div>
                    </div>
                @endif

                <!-- Schedule -->
                <div class="card border-0 shadow-sm mb-3 {{ $doctor->biography ? '' : 'mt-2' }}">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Schedules</span>
                        <a href="{{ route('doctors.schedule', $doctor) }}"
                            class="btn btn-sm btn-outline-primary">Manage</a>
                    </div>
                    <div class="card-body p-2">
                        <div class="table">
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
