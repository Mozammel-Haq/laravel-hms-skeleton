<x-app-layout>
    <div class="container-fluid">
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Tax Report</h3>
                    <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary">Financial</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable datatable-server">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Subtotal</th>
                                <th>VAT 10%</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                @php
                                    $subtotal = $invoice->total_amount ?? ($invoice->total ?? 0);
                                    $vat = $subtotal * 0.1;
                                    $total = $subtotal + $vat;
                                @endphp
                                <tr>
                                    <td>#{{ $invoice->id }}</td>
                                    <td>{{ number_format($subtotal, 2) }}</td>
                                    <td>{{ number_format($vat, 2) }}</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                    <td>{{ $invoice->created_at }}</td>
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
