<x-app-layout>
    <div class="content">

        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Schedule Exceptions</h4>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('doctor.schedule.exceptions.create') }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> New Request
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Clinic</th>
                                        <th>Type</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($exceptions as $exception)
                                        <tr>
                                            <td>{{ $exception->exception_date }}</td>
                                            <td>{{ $exception->clinic->name }}</td>
                                            <td>
                                                @if ($exception->is_available)
                                                    <span class="badge bg-info">Time Change</span>
                                                @else
                                                    <span class="badge bg-danger">Day Off</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($exception->is_available)
                                                    {{ \Carbon\Carbon::parse($exception->start_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($exception->end_time)->format('H:i') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($exception->reason, 30) }}</td>
                                            <td>
                                                @if ($exception->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($exception->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($exception->status == 'pending')
                                                    <form
                                                        action="{{ route('doctor.schedule.exceptions.destroy', $exception) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No exceptions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $exceptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
