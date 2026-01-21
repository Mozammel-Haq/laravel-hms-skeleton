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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clinics as $clinic)
                            <tr>
                                <td>
                                    <a href="{{ route('clinics.show', $clinic) }}"
                                        class="text-decoration-none text-body fw-medium">
                                        {{ $clinic->name }}
                                    </a>
                                </td>
                                <td>{{ $clinic->code }}</td>
                                <td>{{ $clinic->city }}</td>
                                <td>{{ $clinic->country }}</td>
                                <td>
                                    @php
                                        $status = $clinic->status;
                                        $color = match ($status) {
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('clinics.show', $clinic) }}"
                                        class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('clinics.edit', $clinic) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('clinics.destroy', $clinic) }}"
                                        onsubmit="return confirm('Delete this clinic?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
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
