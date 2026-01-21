<x-app-layout>
    <div class="container-fluid">

        <div class="card m-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Payments</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Billing</a>
                    </div>
                </div>
                <hr>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('billing.payments.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Invoice or Patient..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="method" class="form-select">
                                <option value="">All Methods</option>
                                <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="active" {{ request('status') !== 'trashed' ? 'selected' : '' }}>Active
                                </option>
                                <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Trash
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
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('billing.payments.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Patient</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Paid At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ optional($payment->invoice)->invoice_number }}</td>
                                    <td>{{ optional(optional($payment->invoice)->patient)->full_name ?? 'Patient' }}
                                    </td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if ($payment->trashed())
                                            <form action="{{ route('billing.payments.restore', $payment->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this payment?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            @can('delete', $payment)
                                                <form action="{{ route('billing.payments.destroy', $payment) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this payment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No payments recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
