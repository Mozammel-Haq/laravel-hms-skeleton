<x-app-layout>

    <div class="card mt-2 px-3 py-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Wards</h5>
            <a href="{{ route('ipd.wards.create') }}" class="btn btn-primary">Add Ward</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wards as $ward)
                        <tr>
                            <td>{{ $ward->name }}</td>
                            <td>{{ ucfirst($ward->type) }}</td>
                            <td>{{ $ward->floor }}</td>
                            <td><span
                                    class="badge bg-{{ $ward->status === 'active' ? 'success' : 'secondary' }}">{{ $ward->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('ipd.wards.edit', $ward) }}"
                                    class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $wards->links() }}
        </div>
    </div>
</x-app-layout>
