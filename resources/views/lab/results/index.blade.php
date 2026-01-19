<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2">
            <div class="card-body">
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Lab Results</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Recorded At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                                <tr>
                                    <td>{{ optional($result->order->patient)->name }}</td>
                                    <td>{{ optional($result->order->test)->name }}</td>
                                    <td>{{ $result->result_value ?? '' }}</td>
                                    <td>{{ optional($result->recorded_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if ($result->order)
                                            <a href="{{ route('lab.show', $result->order) }}"
                                                class="btn btn-sm btn-primary">View Order</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No results found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $results->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
