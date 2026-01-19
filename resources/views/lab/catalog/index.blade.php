<x-app-layout>

        <div class="card mt-3 p-3 mx-2">
        <h5 class="mb-3">Lab Test Catalog</h5>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
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
