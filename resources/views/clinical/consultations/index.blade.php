<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Consultations</h3>
                    <div class="d-flex gap-2">
                        <div class="btn-group">
                            <a href="{{ route('clinical.consultations.index') }}"
                                class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                            <a href="{{ route('clinical.consultations.index', ['status' => 'trashed']) }}"
                                class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                        </div>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Appointments</a>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable datatable-server">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $c)
                                <tr>
                                    <td>{{ optional($c->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($c->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $c->type ?? 'OPD' }}</td>
                                    <td>
                                        @if ($c->trashed())
                                            <form action="{{ route('clinical.consultations.restore', $c->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this consultation?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('clinical.consultations.show', $c->id) }}"
                                                class="btn btn-sm btn-outline-primary">View</a>
                                            @can('delete', $c)
                                                <form action="{{ route('clinical.consultations.destroy', $c) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this consultation?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No consultations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $consultations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
