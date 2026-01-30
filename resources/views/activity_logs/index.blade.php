<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Activity Logs</h5>
        </div>
        <div class="card-body">

            <!-- Filter Form -->
            <form method="GET" action="{{ route('activity_logs.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search User, Action..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="action" class="form-select">
                            <option value="all" {{ request('action') == 'all' ? 'selected' : '' }}>All Actions
                            </option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created
                            </option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated
                            </option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted
                            </option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
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
                        <a href="{{ route('activity_logs.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Performed By</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activity->actor_name }}</td>
                                <td><span class="badge bg-label-primary">{{ ucfirst($activity->action) }}</span></td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No activities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
