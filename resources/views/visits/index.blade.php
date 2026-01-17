<x-app-layout>

    <div class="card mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3 px-3">
            <h5 class="mb-0">Visits</h5>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('visits.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('visits.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('visits.create') }}" class="btn btn-outline-primary">New Visit</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Appointment</th>
                        <th>Patient</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visits as $visit)
                        <tr>
                            <td>#{{ $visit->appointment_id }}</td>
                            <td>{{ optional($visit->appointment->patient)->name }}</td>
                            <td>{{ $visit->visit_status }}</td>
                            <td class="text-end">
                                @if ($visit->trashed())
                                    <form action="{{ route('visits.restore', $visit->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Are you sure you want to restore this visit?')">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('visits.show', $visit) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('visits.destroy', $visit) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this visit?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $visits->links() }}
        </div>
    </div>
</x-app-layout>
