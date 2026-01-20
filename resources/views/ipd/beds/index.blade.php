<x-app-layout>

    <div class="card mt-2 mx-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Beds</h5>
            <a href="{{ route('ipd.beds.create') }}" class="btn btn-primary">Add Bed</a>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bed</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beds as $bed)
                        <tr>
                            <td>{{ $bed->bed_number }}</td>
                            <td><span
                                    class="badge bg-{{ $bed->status === 'available' ? 'success' : ($bed->status === 'occupied' ? 'danger' : 'secondary') }}">{{ $bed->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('ipd.beds.edit', $bed) }}"
                                    class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $beds->links() }}
        </div>
    </div>
</x-app-layout>
