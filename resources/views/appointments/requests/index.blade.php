<x-app-layout>
    <div class="container-fluid py-1 mx-1">
        <div class="card p-3">
            <div class="page-header d-flex justify-content-between align-items-center px-3 py-3 border-bottom bg-primary-subtle text-primary rounded-top">
                <div class="page-title">
                    <h4 class="fw-bold mb-2 text-primary">Appointment Requests</h4>
                    {{-- breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Appointment Requests</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Requested At</th>
                                <th>Patient</th>
                                <th>Type</th>
                                <th>Original Appointment</th>
                                <th>Desired Change</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $request->appointment?->patient?->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $request->appointment?->patient?->phone ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->type === 'cancel' ? 'danger' : 'warning' }}">
                                            {{ ucfirst($request->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            Dr. {{ $request->appointment?->doctor?->user?->name ?? '-' }}<br>
                                            {{ $request->appointment?->appointment_date?->format('Y-m-d') ?? '-' }}
                                            {{ $request->appointment?->start_time?->format('h:i A') ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($request->type === 'reschedule')
                                            <div class="text-primary fw-bold">
                                                {{ $request->desired_date?->format('Y-m-d') ?? '-' }} <br>
                                                {{ $request->desired_time ? $request->desired_time->format('h:i A') : '-' }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td title="{{ $request->reason }}">{{ Str::limit($request->reason, 50) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <form action="{{ route('appointments.requests.update', $request) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Are you sure you want to approve this request?')">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.requests.update', $request) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to reject this request?')">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No pending requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
