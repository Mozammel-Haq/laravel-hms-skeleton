<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Invoices</h3>
            @can('create', \App\Models\Invoice::class)
                <a href="{{ route('billing.create') }}" class="btn btn-primary">Create Invoice</a>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Issued</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_number }}</td>
                                    <td>{{ optional($inv->patient)->full_name ?? 'Patient' }}</td>
                                    <td><span class="badge bg-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($inv->status) }}</span></td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($inv->issued_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('billing.show', $inv->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
