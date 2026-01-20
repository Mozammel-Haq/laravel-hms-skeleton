<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Consultations</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Appointments</a>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('clinical.consultations.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search by Patient, Doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control" placeholder="From Date" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control" placeholder="To Date" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('clinical.consultations.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
