<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">Super Admin Users</h2>
    </x-slot>
    <div class="card m-2">
        <div class="card-header">
            <h3 class="card-title">Super Admin Users</h3>
        </div>
        <div class="card-body">
            <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Super Admins</h5>
            <a href="{{ route('admin.super-admin-users.create') }}" class="btn btn-primary">Create Super Admin</a>
        </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($superAdmins as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <a href="{{ route('admin.super-admin-users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.super-admin-users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
