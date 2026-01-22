<x-app-layout>


    <div class="card mt-2 mx-2">
        <div class="card-body">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div class="page-title">
                    <h4>Patients</h4>
                    <p class="text-muted">Manage patient records</p>
                </div>
                @can('create', \App\Models\Patient::class)
                    <div class="action-btn">
                        <a href="{{ route('patients.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add Patient
                        </a>
                    </div>
                @endcan
            </div>
            <!-- Filters -->
            <form method="GET" action="{{ route('patients.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trash
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" class="form-control" placeholder="From Date"
                            value="{{ request('from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" class="form-control" placeholder="To Date"
                            value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('patients.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Contact</th>
                            <th>Age/Gender</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}"
                                        class="d-flex align-items-center text-decoration-none text-body">
                                        <div class="avatar avatar-sm me-2">
                                            @if ($patient->profile_photo)
                                                <img src="{{ asset($patient->profile_photo) }}"
                                                    alt="{{ $patient->name }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover;">
                                            @else
                                                <span
                                                    class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    {{ substr($patient->name, 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $patient->name }}</div>
                                            <div class="text-muted small">{{ $patient->patient_code }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div><i class="ti ti-phone me-1 text-muted"></i> {{ $patient->phone }}</div>
                                    @if ($patient->email)
                                        <div class="text-muted small"><i class="ti ti-mail me-1"></i>
                                            {{ $patient->email }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} Years</div>
                                    <div class="text-muted small text-capitalize">{{ $patient->gender }}</div>
                                </td>
                                <td>
                                    @if ($patient->blood_group)
                                        <span
                                            class="badge bg-danger-subtle text-danger">{{ $patient->blood_group }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $status = $patient->status;
                                        $color = match ($status) {
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if ($patient->trashed())
                                                <li>
                                                    <form action="{{ route('patients.restore', $patient->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success"
                                                            onclick="return confirm('Are you sure you want to restore this patient?')">
                                                            <i class="ti ti-rotate-clockwise me-2"></i>Restore
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                @can('view', $patient)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('patients.show', $patient) }}">
                                                            <i class="ti ti-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('update', $patient)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('patients.edit', $patient) }}">
                                                            <i class="ti ti-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete', $patient)
                                                    <li>
                                                        <form action="{{ route('patients.destroy', $patient) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this patient?')">
                                                                <i class="ti ti-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">No patients found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
