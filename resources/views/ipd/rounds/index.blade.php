<x-app-layout>
    <div class="container-fluid mx-2">

        <div class="card border-0 mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Rounds Management</h3>
                    <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">IPD Dashboard</a>
                </div>
                <hr>

                <form method="GET" action="{{ route('ipd.rounds.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Patient or Doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="admitted" {{ request('status') == 'admitted' ? 'selected' : '' }}>
                                    Admitted</option>
                                <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>
                                    Discharged</option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
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
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('ipd.rounds.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admitted On</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ optional($admission->patient)->name ?? 'Patient' }}
                                        </div>
                                        <div class="small text-muted">{{ $admission->patient->patient_code ?? '' }}
                                        </div>
                                    </td>
                                    <td>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $admission->created_at }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('ipd.rounds.create', $admission->id) }}">
                                                        <i class="ti ti-plus me-1"></i> Add Round
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('ipd.show', $admission->id) }}">
                                                        <i class="ti ti-eye me-1"></i> Review
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No admitted patients found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $admissions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
