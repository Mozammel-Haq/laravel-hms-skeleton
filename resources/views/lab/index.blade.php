<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h3 class="page-title mb-0">Lab Orders</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lab.create') }}" class="btn btn-outline-primary">Order Test</a>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('lab.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by ID, Patient, Status..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="all">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed
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
                            <a href="{{ route('lab.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
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
                                    <td>
                                        @if ($order->patient)
                                            <a href="{{ route('patients.show', $order->patient) }}"
                                                class="text-decoration-none text-body">
                                                {{ $order->patient->full_name ?? $order->patient->name }}
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td>{{ optional($order->test)->name ?? 'Test' }}</td>
                                    <td>
                                        @php
                                            $status = $order->status;
                                            $color = match ($status) {
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>{{ isset($order->order_date) ? \Illuminate\Support\Carbon::parse($order->order_date)->format('Y-m-d') : $order->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="text-end">
                                        @if ($order->trashed())
                                            <form action="{{ route('lab.restore', $order->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this order?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('lab.show', $order) }}"
                                                class="btn btn-sm btn-outline-primary">Open</a>
                                            <form action="{{ route('lab.destroy', $order) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this order?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
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
