<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Appointments</h4>
            <p class="text-muted">Manage patient appointments</p>
        </div>
        @can('create', \App\Models\Appointment::class)
        <div class="action-btn">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Book Appointment
            </a>
        </div>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $appointment->appointment_date }}</div>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            {{ substr($appointment->patient->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                        <div class="text-muted small">{{ $appointment->patient->patient_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $appointment->doctor->user->name }}</div>
                                <div class="text-muted small">{{ $appointment->doctor->specialization }}</div>
                            </td>
                            <td>
                                @if($appointment->appointment_type === 'online')
                                    <span class="badge bg-info-subtle text-info">Online</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">In Person</span>
                                @endif
                            </td>
                            <td>
                                @switch($appointment->status)
                                    @case('confirmed')
                                        <span class="badge bg-success-subtle text-success">Confirmed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-primary-subtle text-primary">Completed</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can('view', $appointment)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('appointments.show', $appointment) }}">
                                                <i class="ti ti-eye me-2"></i>View
                                            </a>
                                        </li>
                                        @endcan
                                        @can('update', $appointment)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('appointments.edit', $appointment) }}">
                                                <i class="ti ti-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">No appointments found</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
