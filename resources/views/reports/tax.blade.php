<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Tax Report</h3>
            <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary">Financial</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                            @foreach ($invoices as $row)
                                <tr>
                                    <td>#{{ $row['invoice']->id }}</td>
                                    <td>{{ number_format($row['subtotal'], 2) }}</td>
                                    <td>{{ number_format($row['vat'], 2) }}</td>
                                    <td>{{ number_format($row['total'], 2) }}</td>
                                    <td>{{ $row['invoice']->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
