<x-app-layout>
    <div class="content pb-0">

        <div class="card mt-2 mx-2 px-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Clinics</h5>
                <a href="{{ route('clinics.create') }}" class="btn btn-primary">Create Clinic</a>
            </div>
            <hr>
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
                        @forelse ($clinics as $clinic)
                            <tr>
                                <td>
                                    <a href="{{ route('clinics.show', $clinic) }}"
                                        class="text-decoration-none text-body fw-medium">
                                        {{ $clinic->name }}
                                    </a>
                                </td>
                                <td>{{ $clinic->code }}</td>
                                <td>{{ $clinic->city }}</td>
                                <td>{{ $clinic->country }}</td>
                                <td>
                                    @php
                                        $status = $clinic->status;
                                        $color = match($status) {
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('clinics.show', $clinic) }}"
                                        class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('clinics.edit', $clinic) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('clinics.destroy', $clinic) }}"
                                        onsubmit="return confirm('Delete this clinic?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No clinics found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $clinics->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
