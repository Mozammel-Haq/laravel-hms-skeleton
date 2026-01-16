<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">IPD Dashboard</h3>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('ipd.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('ipd.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-outline-primary">Bed Assignments</a>
                <a href="{{ route('ipd.create') }}" class="btn btn-primary">Admit Patient</a>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Admissions</div>
                        <div class="display-6">{{ $admissionsCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Beds Available</div>
                        <div class="display-6">{{ $bedsAvailable }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Beds Occupied</div>
                        <div class="display-6">{{ $bedsOccupied }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Wards / Rooms</div>
                        <div class="display-6">{{ $totalWards }} / {{ $totalRooms }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admitted On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>{{ optional($admission->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $admission->created_at }}</td>
                                    <td><span class="badge bg-success">{{ $admission->status }}</span></td>
                                    <td>
                                        @if ($admission->trashed())
                                            <form action="{{ route('ipd.restore', $admission->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this admission?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('ipd.show', $admission->id) }}"
                                                class="btn btn-sm btn-outline-primary me-2">View</a>
                                            <a href="{{ route('ipd.assign-bed', $admission->id) }}"
                                                class="btn btn-sm btn-outline-success me-2">Assign Bed</a>
                                            <form action="{{ route('ipd.destroy', $admission) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this admission?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No current admissions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
