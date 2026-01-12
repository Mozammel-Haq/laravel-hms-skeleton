<x-app-layout>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">My Schedule Exceptions</h4>
        <a href="{{ route('doctor.schedule.exceptions.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Request Exception
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($exceptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date Range</th>
                                <th>Clinic</th>
                                <th>Type</th>
                                <th>Time</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exceptions as $exception)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($exception->start_date)->format('M d, Y') }}
                                        @if($exception->start_date != $exception->end_date)
                                            - {{ \Carbon\Carbon::parse($exception->end_date)->format('M d, Y') }}
                                        @endif
                                    </td>
                                    <td>{{ $exception->clinic->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($exception->is_available)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Day Off</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($exception->is_available)
                                            {{ \Carbon\Carbon::parse($exception->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($exception->end_time)->format('h:i A') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($exception->reason, 50) }}</td>
                                    <td>
                                        @if($exception->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($exception->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($exception->status == 'pending')
                                            <form action="{{ route('doctor.schedule.exceptions.destroy', $exception->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $exceptions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ti ti-calendar-off fs-1 text-muted mb-3"></i>
                    <p class="text-muted">No schedule exceptions found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
