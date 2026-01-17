<x-app-layout>
    {{-- Clinic Admin Crud List with delete and Update options --}}
    <div class="card mt-2">
        <div class="card-body">
            <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Clinic Admins</h5>
            <a href="{{ route('admin.clinic-admin-users.create') }}" class="btn btn-primary">Create Clinic Admin</a>
        </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Clinic</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clinicAdmins as $clinicAdmin)
                        <tr>
                            <td>{{ $clinicAdmin->name }}</td>
                            <td>{{ $clinicAdmin->clinic->name }}</td>
                            <td>{{ $clinicAdmin->email }}</td>
                            <td>{{ $clinicAdmin->phone }}</td>
                            <td>{{ $clinicAdmin->status }}</td>

                            <td>
                                <a href="{{ route('admin.clinic-admin-users.edit', $clinicAdmin) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.clinic-admin-users.destroy', $clinicAdmin) }}" method="POST" style="display: inline;">
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
        <div class="card-footer">
            {{ $clinicAdmins->links() }}
        </div>
    </div>




</x-app-layout>
