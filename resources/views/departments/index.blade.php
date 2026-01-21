<x-app-layout>


    {{-- Success message --}}
    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="card mt-2 mx-2">
        <div class="card-body">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div class="page-title">
                    <h4>Departments</h4>
                    <p class="text-muted">Configure clinical departments for the clinic</p>
                </div>
                <div class="d-flex gap-2">
                    @can('create', \App\Models\Department::class)
                        <div class="action-btn">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                <i class="ti ti-plus me-1"></i> Add Department
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
            <!-- Filters -->
            <form method="GET" action="{{ route('departments.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trash
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
                        <a href="{{ route('departments.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="fw-semibold">{{ $department->name }}</td>
                                <td class="text-muted">{{ $department->description ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if ($department->trashed())
                                                @can('delete', $department)
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('departments.restore', $department->id) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"
                                                                onclick="return confirm('Restore this department?')">
                                                                <i class="ti ti-refresh me-2"></i>Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            @else
                                                @can('update', $department)
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editDepartmentModal-{{ $department->id }}">
                                                            <i class="ti ti-edit me-2"></i>Edit
                                                        </button>
                                                    </li>
                                                @endcan

                                                @can('delete', $department)
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('departments.destroy', $department) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Delete this department?')">
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

                            {{-- EDIT MODAL --}}
                            @can('update', $department)
                                <div class="modal fade" id="editDepartmentModal-{{ $department->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('departments.update', $department) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Department</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ $department->name }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea name="description" class="form-control" rows="3">{{ $department->description }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No departments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $departments->links() }}
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    @can('create', \App\Models\Department::class)
        <div class="modal fade" id="addDepartmentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('departments.store') }}">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Add Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

</x-app-layout>
