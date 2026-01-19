<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px;">Tax Report</th>
        </tr>
        <tr>
            <th>Period</th>
            <td>{{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</td>
        </tr>
    </thead>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Summary</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Revenue</td>
            <td>{{ number_format($totalRevenue, 2) }}</td>
        </tr>
        <tr>
            <td>Total Tax (10%)</td>
            <td>{{ number_format($totalTax, 2) }}</td>
        </tr>
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold;">Invoice Details</th>
        </tr>
        <tr>
            <th>Invoice ID</th>
            <th>Patient</th>
            <th>Type</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Tax (10%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>#{{ $invoice->id }}</td>
                <td>{{ $invoice->patient->user->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($invoice->invoice_type) }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                <td>{{ number_format($invoice->total_amount * 0.10, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
