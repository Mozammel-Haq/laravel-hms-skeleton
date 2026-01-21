<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Activity Logs</h3>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('activity.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search User, Action..." value="{{ request('search') }}">
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
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login
                                </option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout
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
                            <a href="{{ route('activity.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Entity ID</th>
                                <th>IP</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ optional($log->user)->name ?? 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ class_basename($log->entity_type) }}</td>
                                    <td>{{ $log->entity_id }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No activity recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
