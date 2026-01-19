<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2 p-2">
            <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Clinic Admin Accounts</h3>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">Roles</a>
        </div>
        <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Clinic</th>
                                <th>Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ optional($user->clinic)->name ?? '—' }}</td>
                                    <td>{{ optional($user->last_login_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
