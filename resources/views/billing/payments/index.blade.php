<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <!-- Header -->
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top mb-0">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Payments</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payments</li>
                        </ol>
                    </nav>
                </div>
                <div>
                        <!-- Optional: Add Create Payment button if applicable, or keep empty if payments are only via invoices -->
                </div>
            </div>

            <div class="card-body">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('billing.payments.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Invoice or Patient..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Method</label>
                            <select name="method" class="form-select form-select-sm">
                                <option value="">All Methods</option>
                                <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ request('status') !== 'trashed' ? 'selected' : '' }}>Active
                                </option>
                                <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Trash
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">From</label>
                            <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">To</label>
                            <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            <a href="{{ route('billing.payments.index') }}" class="btn btn-light btn-sm w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Patient</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Paid At</th>
                                <th class="text-end">Actions</th>
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
                                    <td>
                                        @if($payment->status === 'success')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                        @elseif($payment->status === 'failed')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Failed</span>
                                        @else
                                            <span class="badge bg-light text-dark border">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if ($payment->trashed())
                                                    <li>
                                                        <form
                                                            action="{{ route('billing.payments.restore', $payment->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"
                                                                onclick="return confirm('Are you sure you want to restore this payment?')">
                                                                <i class="ti ti-refresh me-1"></i> Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    @can('delete', $payment)
                                                        <li>
                                                            <form
                                                                action="{{ route('billing.payments.destroy', $payment) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this payment?')">
                                                                    <i class="ti ti-trash me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcan
                                                @endif
                                            </ul>
                                        </div>
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
