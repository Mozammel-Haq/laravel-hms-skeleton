<x-app-layout>
    <div class="content pb-0">

        <div class="card mt-2 mx-2 px-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Clinics</h5>
                <a href="{{ route('clinics.create') }}" class="btn btn-primary">Create Clinic</a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('clinics.index') }}" class="mb-4 mt-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search Name, Code, City..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="all">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="trashed" class="form-select">
                            <option value="">No Trashed</option>
                            <option value="with" {{ request('trashed') == 'with' ? 'selected' : '' }}>With Trashed
                            </option>
                            <option value="only" {{ request('trashed') == 'only' ? 'selected' : '' }}>Only Trashed
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
                        <a href="{{ route('clinics.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clinics as $clinic)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($clinic->logo_path)
                                            <img src="{{ Storage::url($clinic->logo_path) }}"
                                                class="rounded-circle border" width="32" height="32"
                                                style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                style="width: 32px; height: 32px;">
                                                <i class="ti ti-building-hospital text-muted"
                                                    style="font-size: 16px;"></i>
                                            </div>
                                        @endif
                                        <a href="{{ route('clinics.show', $clinic) }}"
                                            class="text-decoration-none text-body fw-medium">
                                            {{ $clinic->name }}
                                            @if ($clinic->trashed())
                                                <small class="text-danger ms-1">(Deleted)</small>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $clinic->code }}</td>
                                <td>{{ $clinic->city }}</td>
                                <td>{{ $clinic->country }}</td>
                                <td>
                                    @if ($clinic->trashed())
                                        <span class="badge bg-danger">Deleted</span>
                                    @else
                                        @php
                                            $status = $clinic->status;
                                            $color = match ($status) {
                                                'active' => 'success',
                                                'inactive' => 'warning',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-icon" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if ($clinic->trashed())
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('clinics.restore', $clinic->id) }}"
                                                        onsubmit="return confirm('Restore this clinic?')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="ti ti-rotate me-1"></i> Restore
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('clinics.show', $clinic) }}">
                                                        <i class="ti ti-eye me-1"></i> View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('clinics.edit', $clinic) }}">
                                                        <i class="ti ti-edit me-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('clinics.destroy', $clinic) }}"
                                                        onsubmit="return confirm('Delete this clinic?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No clinics found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $clinics->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
