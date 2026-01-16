<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Payments</h3>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a href="{{ route('billing.payments.index') }}"
                        class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                    <a href="{{ route('billing.payments.index', ['status' => 'trashed']) }}"
                        class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                </div>
                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Billing</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
