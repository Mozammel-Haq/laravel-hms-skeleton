<x-app-layout>
    <div class="container-fluid">



    <div class="card m-2">
        <div class="card-body">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4>Appointments</h4>
            <p class="text-muted">Manage patient appointments</p>
        </div>
        @can('create', \App\Models\Appointment::class)
            <div class="action-btn d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('appointments.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('appointments.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('appointments.booking.index') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Book Appointment
                </a>
            </div>
        @endcan
    </div>
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
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</div>
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
                                            <div class="text-muted small">{{ $appointment->patient->patient_code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $appointment->doctor?->user?->name ?? 'Deleted Doctor' }}</div>
                                    <div class="text-muted small">{{ $appointment->doctor?->specialization ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if ($appointment->appointment_type === 'online')
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

                                        @case('arrived')
                                            <span class="badge bg-info-subtle text-info">Arrived</span>
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
                                            @if ($appointment->trashed())
                                                <li>
                                                    <form
                                                        action="{{ route('appointments.restore', $appointment->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success"
                                                            onclick="return confirm('Are you sure you want to restore this appointment?')">
                                                            <i class="ti ti-rotate-clockwise me-2"></i>Restore
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                @can('view', $appointment)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('appointments.show', $appointment) }}">
                                                            <i class="ti ti-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('update', $appointment)
                                                    @if ($appointment->status == 'pending')
                                                        <li>
                                                            <form
                                                                action="{{ route('appointments.status.update', $appointment) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="arrived">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ti ti-walk me-2"></i>Mark Arrived
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($appointment->status == 'arrived')
                                                        <li>
                                                            <form action="{{ route('visits.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="appointment_id"
                                                                    value="{{ $appointment->id }}">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ti ti-file-invoice me-2"></i>Start Visit &
                                                                    Generate Bill
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($appointment->status == 'confirmed')
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('clinical.consultations.create', $appointment) }}">
                                                                <i class="ti ti-stethoscope me-2"></i>Start Consultation
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('appointments.edit', $appointment) }}">
                                                            <i class="ti ti-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete', $appointment)
                                                    <li>
                                                        <form action="{{ route('appointments.destroy', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                                <i class="ti ti-trash me-2"></i>Cancel
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            @endif
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
