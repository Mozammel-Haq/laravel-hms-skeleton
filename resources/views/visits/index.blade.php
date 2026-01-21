<x-app-layout>

    <div class="card mt-2 mx-2 p-3">
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 mb-2">
            <h5 class="mb-0">Visits</h5>

            <div class="d-flex gap-2">

                <a href="{{ route('visits.create') }}" class="btn btn-outline-primary">New Visit</a>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('visits.index') }}" class="mb-4 px-3 mt-2">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Patient, Visit ID..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" placeholder="From Date"
                        value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" placeholder="To Date"
                        value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('visits.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
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
                            <td>{{ optional($visit?->appointment?->patient)?->name }}</td>
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
