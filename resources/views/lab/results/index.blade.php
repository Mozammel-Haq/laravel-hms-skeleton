<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Lab Results</h3>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('lab.results.index') }}" class="mb-4 mt-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by Patient, Test, Result..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="from" class="form-control" placeholder="From Date" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to" class="form-control" placeholder="To Date" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Reported At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                                <tr>
                                    <td>{{ optional($result->order->patient)->name }}</td>
                                    <td>{{ optional($result->order->test)->name }}</td>
                                    <td>{{ $result->result_value ?? '' }}</td>
                                    <td>{{ optional($result->reported_at)->format('Y-m-d H:i') }}</td>
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
