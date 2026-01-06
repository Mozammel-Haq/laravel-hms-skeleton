<x-app-layout>
    @php
        $logs = \App\Models\ActivityLog::with('user')->latest()->take(100)->get();
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Activity Logs</h3>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
        <div class="card">
            <div class="card-body">
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
                                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No activity recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
