<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card border-0 shadow-sm">

            <div class="card-body p-0 pt-3">
                            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary p-3 mx-2 rounded-top">
                <div>
                    <h4 class="fw-bold mb-2 text-primary">Doctors</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Doctors</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('doctors.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus me-1"></i> Create Doctor
                </a>
            </div>
            <hr>
                    <!-- Filters -->
                    <form method="GET" action="{{ route('doctors.index') }}" class="mb-4 px-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-muted mb-1">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, email..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                    <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trash</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-muted mb-1">From Date</label>
                                <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                    value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-muted mb-1">To Date</label>
                                <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                    value="{{ request('to') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                                    <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-light border w-100">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                @if ($doctor->user)
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                        class="d-flex align-items-center text-decoration-none text-body">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="{{ $doctor->user->name }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $doctor->user->name }}</div>
                                            <div class="small text-muted">{{ $doctor->user->email }}</div>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="Deleted Doctor" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Deleted Doctor</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $doctor->department->name ?? 'N/A' }}</td>
                            <td>
                                @if ($doctor->consultation_room_number || $doctor->consultation_floor)
                                    <div class="small">
                                        @if ($doctor->consultation_room_number)
                                            <div>Room: {{ $doctor->consultation_room_number }}</div>
                                        @endif
                                        @if ($doctor->consultation_floor)
                                            <div class="text-muted">Floor: {{ $doctor->consultation_floor }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
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
                                @if(count($pieces) > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($pieces as $spec)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10">{{ $spec }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $doctor->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($doctor->trashed())
                                            <li>
                                                <form action="{{ route('doctors.restore', $doctor->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="ti ti-refresh me-1"></i> Restore
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.show', $doctor) }}">View Details</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.edit', $doctor) }}">Edit Info</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.schedule', $doctor) }}">Manage Schedule</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('doctors.destroy', $doctor) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No doctors found. <a href="{{ route('doctors.create') }}">Add your first doctor</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                    </div>
                    @if ($doctors->hasPages())
                        <div class="mt-4">
                            {{ $doctors->links() }}
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
