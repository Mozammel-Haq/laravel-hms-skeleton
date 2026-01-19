<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-3 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Patient Demographics</h3>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reports Hub</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Gender</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genderStats as $row)
                                <tr>
                                    <td>{{ ucfirst($row->gender ?? 'unknown') }}</td>
                                    <td>{{ $row->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
