<x-app-layout>
<div class="content">

    <h6 class="fs-14 mb-3">
        <a href="{{ route('admin.roles.index') }}">
            <i class="ti ti-chevron-left me-1"></i>Roles
        </a>
    </h6>

    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-0">Permissions</h4>
        </div>

        <div class="text-end d-flex">
            <div class="dropdown">
                <button class="btn bg-white border dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="text-body me-1">Role :</span>
                    {{ $role->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end p-2">
                    @foreach($roles as $r)
                        <li>
                            <a class="dropdown-item rounded-1" href="{{ route('admin.permissions.index', ['role' => $r->id]) }}">
                                {{ $r->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.permissions.updateRolePermissions', $role->id) }}">
        @csrf
        @method('PUT')

        @foreach($permissions as $module => $modulePermissions)
            @php $moduleSlug = Str::slug($module); @endphp
            <div class="card mb-3">

                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0">{{ $module }}</h6>
                        <div class="form-check form-check-md">
                            <input class="form-check-input select-module" type="checkbox" data-module="{{ $moduleSlug }}">
                            <label>Allow All</label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive border">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th>Module</th>
                                    <th>CREATE</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                    <th>VIEW</th>
                                    <th>ALLOW ALL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modulePermissions->groupBy('entity') as $entity => $actions)
                                    @php
                                        $create = optional($actions->firstWhere('action', 'create'));
                                        $edit = optional($actions->firstWhere('action', 'edit'));
                                        $delete = optional($actions->firstWhere('action', 'delete'));
                                        $view = optional($actions->firstWhere('action', 'view'));
                                    @endphp
                                    <tr>
                                        <td>
                                            <p class="fw-medium text-dark">{{ ucfirst($entity) }}</p>
                                        </td>

                                        <td>
                                            @if($create)
                                                <div class="form-check form-check-md">
                                                    <input class="form-check-input permission-checkbox {{ $moduleSlug }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $create->id }}"
                                                           @checked(in_array($create->name, $rolePermissions))>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($edit)
                                                <div class="form-check form-check-md">
                                                    <input class="form-check-input permission-checkbox {{ $moduleSlug }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $edit->id }}"
                                                           @checked(in_array($edit->name, $rolePermissions))>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($delete)
                                                <div class="form-check form-check-md">
                                                    <input class="form-check-input permission-checkbox {{ $moduleSlug }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $delete->id }}"
                                                           @checked(in_array($delete->name, $rolePermissions))>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($view)
                                                <div class="form-check form-check-md">
                                                    <input class="form-check-input permission-checkbox {{ $moduleSlug }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $view->id }}"
                                                           @checked(in_array($view->name, $rolePermissions))>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input row-select">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
document.addEventListener('DOMContentLoaded', function () {

    // Module-level select all
    document.querySelectorAll('.select-module').forEach(moduleCheckbox => {
        moduleCheckbox.addEventListener('change', function () {
            const moduleSlug = this.dataset.module;
            if (!moduleSlug) return;
            document.querySelectorAll('.permission-checkbox.' + moduleSlug).forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });

    // Row-level select all
    document.querySelectorAll('.row-select').forEach(rowCheckbox => {
        rowCheckbox.addEventListener('change', function () {
            this.closest('tr').querySelectorAll('.permission-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });

});
</script>
</x-app-layout>
