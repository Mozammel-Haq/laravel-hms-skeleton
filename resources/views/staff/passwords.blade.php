<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3 rounded shadow-sm">
            <div>
                <h4 class="fw-bold mb-2 text-primary">Reset Passwords</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reset Passwords</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('staff.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-users me-1"></i> Manage Staff
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table">
                    <table class="table table-hover align-middle datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staff as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('staff.edit', $user->id) }}">
                                                        <i class="ti ti-key me-1"></i> Reset Password
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $staff->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
