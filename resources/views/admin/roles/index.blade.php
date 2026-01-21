<x-app-layout>

    <div class="content">
        <div class="card p-3 mx-2">
            <!-- Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Roles</h4>
                </div>
                <div class="text-end d-flex">
                    <button class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal" data-bs-target="#add_role">
                        <i class="ti ti-plus me-1"></i> New Role
                    </button>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Search Roles..."
                            value="{{ request('search') }}">
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
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>

            <hr>

            <!-- Roles Table -->
            <div class="table-responsive">
                <table class="table table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Role</th>
                            <th>Users Count</th>
                            <th>Created On</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td>{{ $role->created_at->format('d M Y') }}</td>
                                <td>{{ $role->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.permissions.index', ['role' => $role->id]) }}"
                                        class="btn btn-white border text-dark btn-sm">
                                        <i class="ti ti-shield-half me-1"></i> Permissions
                                    </a>
                                </td>
                                <td>
                                    <div class="action-item">
                                        <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu p-2">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#edit_role" data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}"
                                                    data-description="{{ $role->description }}">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                    data-bs-target="#delete_role" data-id="{{ $role->id }}">
                                                    Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        </div>

    </div>

    <div id="add_role" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.roles.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title fw-bold">New Role</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Role</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Start Add Modal -->
    <div id="edit_role" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editRoleForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h4 class="modal-title fw-bold">Edit Role</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_role_name" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_role_description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- End Add Modal -->

    <!-- Start Delete Modal  -->
    <div id="delete_role" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="deleteRoleForm" method="POST" class="modal-content text-center">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <span class="avatar avatar-lg bg-danger text-white mb-3">
                        <i class="ti ti-trash fs-24"></i>
                    </span>
                    <h5 class="fw-bold">Delete Confirmation</h5>
                    <p>Are you sure you want to delete this role?</p>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- End Delete Modal  -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Edit Role
            document.querySelectorAll('[data-bs-target="#edit_role"]').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const description = this.dataset.description;

                    document.getElementById('edit_role_name').value = name;
                    document.getElementById('edit_role_description').value = description ?? '';

                    document.getElementById('editRoleForm').action =
                        `/admin/roles/${id}`;
                });
            });

            // Delete Role
            document.querySelectorAll('[data-bs-target="#delete_role"]').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    document.getElementById('deleteRoleForm').action =
                        `/admin/roles/${id}`;
                });
            });

        });
    </script>

</x-app-layout>
