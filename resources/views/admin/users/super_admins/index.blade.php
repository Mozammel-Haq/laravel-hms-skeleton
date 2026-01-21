<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">Super Admin Users</h2>
    </x-slot>
    <div class="card m-2 p-2">
        <div class="card-header">
            <h3 class="card-title">Super Admin Users</h3>
        </div>
        <hr>
        <div class="card-body">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Super Admins</h5>
                <a href="{{ route('admin.super-admin-users.create') }}" class="btn btn-primary">Create Super Admin</a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.super-admin-users.index') }}" class="mb-4 mt-3">
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
                        <a href="{{ route('admin.super-admin-users.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($superAdmins as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <a href="{{ route('admin.super-admin-users.edit', $user) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.super-admin-users.destroy', $user) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No super admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $superAdmins->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
