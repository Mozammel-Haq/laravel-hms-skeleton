<x-app-layout>


    <div class="card border-0 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                                <div class="d-flex align-items-center">
                                    <div
                                        class="avatar avatar-sm me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        {{ substr($doctor->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $doctor->user->name }}</div>
                                        <div class="small text-muted">{{ $doctor->user->email }}</div>
                                    </div>
                                </div>
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
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        Actions
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
