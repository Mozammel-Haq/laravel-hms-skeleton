<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Lab Orders</h3>
            <a href="{{ route('lab.create') }}" class="btn btn-outline-primary">Order Test</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Status</th>
                                <th>Ordered</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ optional($order->patient)->full_name ?? (optional($order->patient)->name ?? 'Patient') }}
                                    </td>
                                    <td>{{ optional($order->test)->name ?? 'Test' }}</td>
                                    <td><span
                                            class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ isset($order->ordered_at) ? \Illuminate\Support\Carbon::parse($order->ordered_at) : $order->created_at }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('lab.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
