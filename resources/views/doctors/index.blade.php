<x-app-layout>


    <div class="card border-0 mt-3 mx-2 py-3">
        <div class="d-flex justify-content-between align-items-center px-3">
            <h3 class="page-title mb-0">Doctors</h3>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('doctors.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('doctors.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add New Doctor
                </a>
            </div>
        </div>
        <hr>
        <div class="table">
            <table class="table table-hover align-middle mb-0 datatable datatable-server">
                <thead class="table-light">
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                @if ($doctor->user)
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                        class="d-flex align-items-center text-decoration-none text-body">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="{{ $doctor->user->name }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $doctor->user->name }}</div>
                                            <div class="small text-muted">{{ $doctor->user->email }}</div>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ asset('assets/img/doctors/doctor-01.jpg') }}"
                                                alt="Deleted Doctor" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Deleted Doctor</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $doctor->department->name ?? 'N/A' }}</td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>
                                <span class="badge bg-{{ $doctor->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($doctor->trashed())
                                            <li>
                                                <form action="{{ route('doctors.restore', $doctor->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="ti ti-refresh me-1"></i> Restore
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.show', $doctor) }}">View Details</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.edit', $doctor) }}">Edit Info</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('doctors.schedule', $doctor) }}">Manage Schedule</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('doctors.destroy', $doctor) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No doctors found. <a href="{{ route('doctors.create') }}">Add your first doctor</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($doctors->hasPages())
            <div class="card-footer bg-transparent">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
