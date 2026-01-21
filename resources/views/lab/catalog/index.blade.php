<x-app-layout>

    <div class="card mt-3 p-3 mx-2">
        <h5 class="mb-3">Lab Test Catalog</h5>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('lab.catalog.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Test Name or Category..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
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
                    <a href="{{ route('lab.catalog.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tests as $test)
                        <tr>
                            <td>{{ $test->name }}</td>
                            <td>{{ $test->category }}</td>
                            <td>{{ number_format($test->price, 2) }}</td>
                            <td><span
                                    class="badge bg-{{ $test->status === 'active' ? 'success' : 'secondary' }}">{{ $test->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $tests->links() }}
        </div>
    </div>
</x-app-layout>
