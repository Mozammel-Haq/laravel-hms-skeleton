<x-app-layout>


    <div class="card mt-2 mx-2 p-3">
        <div class="card-body">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div class="page-title">
                    <h4>Staff & Users</h4>
                    <p class="text-muted">Manage clinic user accounts and roles</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group">
                        <a href="{{ route('staff.index') }}"
                            class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                        <a href="{{ route('staff.index', ['status' => 'trashed']) }}"
                            class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                    </div>
                    @can('create', \App\Models\User::class)
                        <div class="action-btn">
                            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                                <i class="ti ti-user-plus me-1"></i> Create User
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('staff.show', $user) }}" class="text-decoration-none text-body fw-bold">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        Active
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown position-relative">
                                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown"
                                            data-bs-display="static">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if ($user->trashed())
                                                @can('delete', $user)
                                                    <li>
                                                        <form action="{{ route('staff.restore', $user->id) }}"
                                                            method="POST">
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
                                <td colspan="5" class="text-center text-muted">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</x-app-layout>
