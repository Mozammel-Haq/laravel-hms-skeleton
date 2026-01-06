<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Lab Test Catalog</h5>
        <a href="{{ route('lab.catalog.create') }}" class="btn btn-primary">Add Test</a>
    </div>
    <div class="card">
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
                @foreach($tests as $test)
                    <tr>
                        <td>{{ $test->name }}</td>
                        <td>{{ $test->category }}</td>
                        <td>{{ number_format($test->price,2) }}</td>
                        <td><span class="badge bg-{{ $test->status === 'active' ? 'success' : 'secondary' }}">{{ $test->status }}</span></td>
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
