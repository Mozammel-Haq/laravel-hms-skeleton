<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Doctors</h4>
            <p class="text-muted">Manage doctors, departments and schedules</p>
        </div>
        @can('create', \App\Models\Doctor::class)
        <div class="action-btn">
            <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                <i class="ti ti-user-plus me-1"></i> Add Doctor
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
                            <th>Department</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dr. John Doe</td>
                            <td>Cardiology</td>
                            <td>Interventional Cardiology</td>
                            <td><span class="badge bg-success-subtle text-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <ul class="dropdown-menu">
                                        @can('update', new \App\Models\Doctor)
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-calendar me-2"></i>Manage Schedule</a></li>
                                        @endcan
                                        @can('delete', new \App\Models\Doctor)
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