<x-app-layout>
    <div class="container-fluid mt-2 mx-2">
            <style>
        .table-responsive {
            /* Ensure there's space at the bottom for dropdowns to expand without being clipped */
            padding-bottom: 0px;
            /* Optional: visible only when scrolling is actually needed?
               No, because dropdown clipping happens even if x-scroll isn't active but overflow-x is auto.
               But always adding 80px padding might look ugly.

               Better approach:
               If the table has few rows, the container height collapses, clipping the dropdown.
               We enforce a minimum height or padding.
            */
            min-height: 0px; /* Ensure enough height for at least one row + dropdown */
            overflow-y: visible !important; /* Try to allow vertical overflow */
            overflow-x: auto;
        }

        /* Fix for last row dropdowns being clipped in responsive tables */

    </style>
        <div class="card">
            <div class="bg-primary-subtle text-primary px-4 py-3 rounded-top">
                <h4 class="fw-bold mb-2 text-primary">Consultations</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Consultations</li>
                    </ol>
                </nav>
            </div>
            <div class="card-body">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('clinical.consultations.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by Patient, Doctor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="all">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed
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
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('clinical.consultations.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $c)
                                <tr>
                                    <td>{{ optional($c->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($c->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $c->type ?? 'OPD' }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if ($c->trashed())
                                                    <li>
                                                        <form
                                                            action="{{ route('clinical.consultations.restore', $c->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"
                                                                onclick="return confirm('Are you sure you want to restore this consultation?')">
                                                                <i class="ti ti-refresh me-1"></i> Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('clinical.consultations.show', $c->id) }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </a>
                                                    </li>
                                                    @can('delete', $c)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('clinical.consultations.destroy', $c) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this consultation?')">
                                                                    <i class="ti ti-trash me-1"></i> Delete
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
                                    <td colspan="5">No consultations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $consultations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
