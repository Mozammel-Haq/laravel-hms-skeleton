<x-app-layout>
    <div class="container-fluid mx-2 my-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 pt-3">
            <div>
                <h4 class="font-bold mb-2 text-primary">Wards</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wards</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('ipd.wards.create') }}" class="btn btn-primary">Add Ward</a>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('ipd.wards.index') }}" class="my-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Ward Name..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
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
                    <a href="{{ route('ipd.wards.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wards as $ward)
                        <tr>
                            <td>{{ $ward->name }}</td>
                            <td>{{ ucfirst($ward->type) }}</td>
                            <td>{{ $ward->floor }}</td>
                            <td><span
                                    class="badge bg-{{ $ward->status === 'active' ? 'success' : 'secondary' }}">{{ $ward->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-icon" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('ipd.wards.edit', $ward) }}">
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
            {{ $wards->links() }}
        </div>
    </div>
</x-app-layout>
