<x-app-layout>
    {{-- Clinic Admin Crud List with delete and Update options --}}
    <div class="card mt-2 mx-2">
        <div class="card-body">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Clinic Admins</h5>
                <a href="{{ route('admin.clinic-admin-users.create') }}" class="btn btn-primary">Create Clinic Admin</a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.clinic-admin-users.index') }}" class="mb-4 mt-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search Name, Email, Phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="all">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked
                            </option>
                            <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed
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
                        <a href="{{ route('admin.clinic-admin-users.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Clinic</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clinicAdmins as $clinicAdmin)
                        <tr>
                            <td>{{ $clinicAdmin->name }}</td>
                            <td>
                                <a href="{{ route('clinics.show', $clinicAdmin->clinic) }}"
                                    class="text-decoration-none text-body">
                                    {{ $clinicAdmin->clinic->name }}
                                </a>
                            </td>
                            <td>{{ $clinicAdmin->email }}</td>
                            <td>{{ $clinicAdmin->phone }}</td>
                            <td>{{ $clinicAdmin->status }}</td>

                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.clinic-admin-users.edit', $clinicAdmin) }}">
                                                <i class="ti ti-edit me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form
                                                action="{{ route('admin.clinic-admin-users.destroy', $clinicAdmin) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this clinic admin?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No clinic admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $clinicAdmins->links() }}
        </div>
    </div>




</x-app-layout>
