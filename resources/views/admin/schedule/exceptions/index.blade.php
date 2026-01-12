<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="page-title mb-0">Schedule Exception Requests</h3>
                <div class="text-muted">Review and approve doctor schedule changes</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Date Range</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Reason</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exceptions as $exception)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    {{ substr($exception->doctor->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $exception->doctor->user->name }}</div>
                                                <div class="text-muted small">{{ $exception->doctor->specialization }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($exception->start_date)->format('M d, Y') }}
                                            @if($exception->start_date != $exception->end_date)
                                                - {{ \Carbon\Carbon::parse($exception->end_date)->format('M d, Y') }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($exception->start_date)->format('l') }}
                                            @if($exception->start_date != $exception->end_date)
                                                - {{ \Carbon\Carbon::parse($exception->end_date)->format('l') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($exception->is_available)
                                            <span class="badge bg-info-subtle text-info">Custom Hours</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Day Off</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($exception->is_available)
                                            {{ \Carbon\Carbon::parse($exception->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($exception->end_time)->format('H:i') }}
                                        @else
                                            <span class="text-muted">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($exception->reason)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $exception->reason }}">
                                                {{ $exception->reason }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.schedule.exceptions.update', $exception) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.schedule.exceptions.update', $exception) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ti ti-calendar-check fs-2 mb-2 d-block"></i>
                                            No pending schedule exception requests.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($exceptions->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $exceptions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>