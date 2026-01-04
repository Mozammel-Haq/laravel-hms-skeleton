<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Staff & Users</h4>
            <p class="text-muted">Manage clinic user accounts and roles</p>
        </div>
        @can('create', \App\Models\User::class)
        <div class="action-btn">
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <i class="ti ti-user-plus me-1"></i> Create User
            </a>
        </div>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mozammel Haq</td>
                            <td>admin@clinic.com</td>
                            <td><span class="badge bg-primary-subtle text-primary">Clinic Admin</span></td>
                            <td><span class="badge bg-success-subtle text-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <ul class="dropdown-menu">
                                        @can('update', new \App\Models\User)
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2"></i>Edit</a></li>
                                        @endcan
                                        @can('delete', new \App\Models\User)
                                        <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-trash me-2"></i>Delete</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>