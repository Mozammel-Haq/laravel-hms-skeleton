<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Staff & Users</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff</li>
                    </ol>
                </nav>
            </div>
            @can('create', \App\Models\User::class)
                <a href="{{ route('staff.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-user-plus me-1"></i> Create User
                </a>
            @endcan
        </div>

        <div class="card shadow-sm rounded-bottom mt-0">
            <div class="card-body p-4">
                <!-- Filter Form -->
                <div class="bg-light p-3 rounded mb-4">
                    <form method="GET" action="{{ route('staff.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Name or Email..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Role</label>
                                <select name="role" class="form-select form-select-sm">
                                    <option value="">All Roles</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">From</label>
                                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">To</label>
                                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="ti ti-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="small text-uppercase text-muted">Name</th>
                                <th class="small text-uppercase text-muted">Email</th>
                                <th class="small text-uppercase text-muted">Roles</th>
                                <th class="small text-uppercase text-muted">Status</th>
                                <th class="text-end small text-uppercase text-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($user->profile_photo_url)
                                                <img src="{{ $user->profile_photo_url }}" class="rounded-circle" width="32" height="32" alt="">
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <a href="{{ route('staff.show', $user) }}" class="text-decoration-none text-body fw-bold">
                                                {{ $user->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="small">{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->trashed())
                                            <span class="badge bg-danger-subtle text-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                @if ($user->trashed())
                                                    @can('delete', $user)
                                                        <li>
                                                            <form action="{{ route('staff.restore', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success"
                                                                    onclick="return confirm('Are you sure you want to restore this user?')">
                                                                    <i class="ti ti-refresh me-2"></i>Restore
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcan
                                                @else
                                                    @can('update', $user)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('staff.edit', $user) }}">
                                                                <i class="ti ti-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('delete', $user)
                                                        <li>
                                                            <form action="{{ route('staff.destroy', $user) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this user?')">
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
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users-off fs-1 mb-2"></i>
                                            <p class="mb-0">No staff members found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
