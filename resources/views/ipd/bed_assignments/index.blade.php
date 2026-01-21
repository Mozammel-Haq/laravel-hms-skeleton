<x-app-layout>

    <div class="card m-2 p-3">
        <h5 class="mb-3">Bed Assignments</h5>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('ipd.bed_assignments.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Patient..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released
                        </option>
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
                    <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bed</th>
                        <th>Patient</th>
                        <th>Assigned At</th>
                        <th>Released At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $assignment)
                        <tr>
                            <td>{{ optional($assignment->bed)->bed_number }}</td>
                            <td>{{ optional($assignment->admission->patient)->name }}</td>
                            <td>{{ $assignment->assigned_at }}</td>
                            <td>{{ $assignment->released_at }}</td>
                            <td class="text-end">
                                <a href="{{ route('ipd.bed_assignments.show', $assignment) }}"
                                    class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $assignments->links() }}
        </div>
    </div>
</x-app-layout>
