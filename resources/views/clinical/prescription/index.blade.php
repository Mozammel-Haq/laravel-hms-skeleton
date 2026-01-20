<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="page-title mb-0">Prescriptions</h3>
                </div>
                
                <!-- Filter Form -->
                <form method="GET" action="{{ route('clinical.prescriptions.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search by ID, Patient, Doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control" placeholder="From Date" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control" placeholder="To Date" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('clinical.prescriptions.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Issued</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prescriptions as $rx)
                                <tr>
                                    <td>{{ $rx->id }}</td>
                                    <td>
                                        @if (optional($rx->consultation)->patient)
                                            <a href="{{ route('patients.show', $rx->consultation->patient) }}"
                                                class="text-decoration-none text-body">
                                                {{ $rx->consultation->patient->full_name ?? $rx->consultation->patient->name }}
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td>
                                        @if (optional($rx->consultation)->doctor)
                                            <a href="{{ route('doctors.show', $rx->consultation->doctor) }}"
                                                class="text-decoration-none text-body">
                                                {{ optional($rx->consultation->doctor->user)->name ?? 'Doctor' }}
                                            </a>
                                        @else
                                            Doctor
                                        @endif
                                    </td>
                                    <td>{{ isset($rx->issued_at) ? \Illuminate\Support\Carbon::parse($rx->issued_at)->format('Y-m-d') : $rx->created_at->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        @php
                                            $rxStatus = $rx->status ?? 'active';
                                            $rxColor = match ($rxStatus) {
                                                'active' => 'success',
                                                'trashed', 'cancelled' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $rxColor }}">{{ ucfirst($rxStatus) }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if ($rx->trashed())
                                            <form action="{{ route('clinical.prescriptions.restore', $rx->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this prescription?')">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('clinical.prescriptions.show', $rx) }}"
                                                class="btn btn-sm btn-outline-primary">Open</a>
                                            @if (auth()->check() && auth()->user()->hasRole('Pharmacist'))
                                                <a href="{{ route('pharmacy.create', ['prescription_id' => $rx->id]) }}"
                                                    class="btn btn-sm btn-outline-success">Load to POS</a>
                                            @endif
                                            <form action="{{ route('clinical.prescriptions.destroy', $rx) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this prescription?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No prescriptions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $prescriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
