<x-app-layout>
    <div class="container-fluid py-4 px-2">
        <!-- Header -->
        <div class="row align-items-center bg-primary-subtle text-primary px-4 py-3 pt-3 mx-2">
            <div class="col ">
                <h4 class="mb-1 fw-bold text-dark">Bed Assignments</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bed Assignments</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row px-2 mt-2">
            <div class="col-lg-12">
                <div class="card  p-3">
                    <h5 class="mb-3">Bed Assignments</h5>

    <div class="card p-2">

        <!-- Filter Form -->
        <form method="GET" action="{{ route('ipd.bed_assignments.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Patient..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released
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
                    <a href="{{ route('ipd.bed_assignments.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bed</th>
                        <th>Patient</th>
                        <th>Assigned At</th>
                        <th>Released At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $assignment)
                        <tr>
                            <td>{{ optional($assignment->bed)->bed_number }}</td>
                            <td>{{ optional($assignment->admission->patient)->name }}</td>
                            <td>{{ $assignment->assigned_at }}</td>
                            <td>{{ $assignment->released_at }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('ipd.bed_assignments.show', $assignment) }}">
                                                <i class="ti ti-eye me-1"></i> View
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $assignments->links() }}
        </div>
    </div>
</x-app-layout>
