<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">Clinics</h2>
    </x-slot>
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Clinics</h5>
            <a href="{{ route('clinics.create') }}" class="btn btn-primary">Create Clinic</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($clinics as $clinic)
                    <tr>
                        <td>{{ $clinic->name }}</td>
                        <td>{{ $clinic->code }}</td>
                        <td>{{ $clinic->city }}</td>
                        <td>{{ $clinic->country }}</td>
                        <td><span class="badge bg-secondary">{{ $clinic->status }}</span></td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('clinics.show', $clinic) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('clinics.edit', $clinic) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('clinics.destroy', $clinic) }}" onsubmit="return confirm('Delete this clinic?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $clinics->links() }}
        </div>
    </div>
</x-app-layout>
