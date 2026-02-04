<x-app-layout>
    <div class="container mx-1 mt-2">
            <div class="bg-primary-subtle text-primary px-4 py-2 pt-3 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-bold mb-2 text-primary">Beds</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}" class="text-decoration-none">IPD</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Beds</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('ipd.beds.create') }}" class="btn btn-primary">Add Bed</a>
        </div>
    <div class="card p-3">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('ipd.beds.index') }}" class="my-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search Bed Number..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                        </option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied
                        </option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                            Maintenance</option>
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
                    <a href="{{ route('ipd.beds.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bed</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beds as $bed)
                        <tr>
                            <td>{{ $bed->bed_number }}</td>
                            <td><span
                                    class="badge bg-{{ $bed->status === 'available' ? 'success' : ($bed->status === 'occupied' ? 'danger' : 'secondary') }}">{{ $bed->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('ipd.beds.edit', $bed) }}">
                                                <i class="ti ti-edit me-1"></i> Edit
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
            {{ $beds->links() }}
        </div>
    </div>
</x-app-layout>
