<x-app-layout>
    <div class="content">

        <h6 class="fs-14 mb-3">
            <a href="{{ route('admin.roles.index') }}">
                <i class="ti ti-chevron-left me-1"></i>Roles
            </a>
        </h6>

        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
            <h4 class="fw-bold mb-0">Permissions for Role: {{ $role->name }}</h4>

            <div class="dropdown">
                <button class="btn bg-white border dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="text-body me-1">Role :</span>
                    {{ $role->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end p-2">
                    @foreach ($roles as $r)
                        <li>
                            <a class="dropdown-item rounded-1"
                                href="{{ route('admin.permissions.index', ['role' => $r->id]) }}">
                                {{ $r->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.permissions.updateRolePermissions', $role->id) }}">
            @csrf
            @method('PUT')

            @foreach ($permissions as $entity => $actions)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            {{ ucfirst(str_replace('_', ' ', $entity)) }}
                        </h6>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input select-entity"
                                data-entity="{{ $entity }}">
                            <label class="form-check-label">Allow All</label>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 datatable">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 180px;">
                                            {{ ucfirst(str_replace('_', ' ', $entity)) }}
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-3">
                                                @foreach ($actions as $action => $perm)
                                                    <label class="form-check form-check-inline mb-0">
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox {{ $entity }}"
                                                            name="permissions[]" value="{{ $perm->id }}"
                                                            @checked(in_array($perm->id, $rolePermissions))>
                                                        {{ strtoupper($action) }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary px-4">Save Permissions</button>
            </div>
        </form>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Entity-level "Allow All"
            document.querySelectorAll('.select-entity').forEach(entityCheckbox => {
                entityCheckbox.addEventListener('change', function() {
                    const entity = this.dataset.entity;
                    const allPermissions = document.querySelectorAll('.permission-checkbox.' +
                        entity);
                    allPermissions.forEach(cb => cb.checked = this.checked);
                });
            });

            // Individual permission changes update the entity "Allow All"
            document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
                permissionCheckbox.addEventListener('change', function() {
                    const entity = [...this.classList].find(cls => cls !== 'form-check-input' &&
                        cls !== 'permission-checkbox');
                    if (!entity) return;

                    const entityCheckbox = document.querySelector('.select-entity[data-entity="' +
                        entity + '"]');
                    if (!entityCheckbox) return;

                    const allPermissions = document.querySelectorAll('.permission-checkbox.' +
                        entity);
                    const allChecked = Array.from(allPermissions).every(cb => cb.checked);

                    entityCheckbox.checked = allChecked;
                });
            });

            // On page load, initialize entity-level "Allow All" checkboxes
            document.querySelectorAll('.select-entity').forEach(entityCheckbox => {
                const entity = entityCheckbox.dataset.entity;
                const allPermissions = document.querySelectorAll('.permission-checkbox.' + entity);
                entityCheckbox.checked = Array.from(allPermissions).every(cb => cb.checked);
            });

        });
    </script>

</x-app-layout>
