<x-app-layout>
    <div class="container-fluid">
        <div class="card m-2">
            <div class="card-body">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <div class="page-title">
                        <h4>Appointment Requests</h4>
                        <p class="text-muted">Manage cancellation and rescheduling requests</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                        <div class="fw-bold">{{ $request->appointment->patient->name }}</div>
                                        <div class="text-muted small">{{ $request->appointment->patient->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->type === 'cancel' ? 'danger' : 'warning' }}">
                                            {{ ucfirst($request->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            Dr. {{ $request->appointment->doctor->user->name }}<br>
                                            {{ $request->appointment->appointment_date->format('Y-m-d') }} 
                                            {{ \Carbon\Carbon::parse($request->appointment->start_time)->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->type === 'reschedule')
                                            <div class="text-primary fw-bold">
                                                {{ $request->desired_date->format('Y-m-d') }} <br>
                                                {{ \Carbon\Carbon::parse($request->desired_time)->format('h:i A') }}
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
                                            <form action="{{ route('appointments.requests.update', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.requests.update', $request) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
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
