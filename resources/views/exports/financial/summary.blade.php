<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px;">Financial Report Summary</th>
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
            <th colspan="2" style="font-weight: bold;">Key Performance Indicators</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Revenue</td>
            <td>{{ number_format($revenue, 2) }}</td>
        </tr>
        <tr>
            <td>Paid Amount</td>
            <td>{{ number_format($paid, 2) }}</td>
        </tr>
        <tr>
            <td>Pending Amount</td>
            <td>{{ number_format($pending, 2) }}</td>
        </tr>
        <tr>
            <td>Total Invoices</td>
            <td>{{ $invoiceCount }}</td>
        </tr>
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Revenue by Type</th>
        </tr>
        <tr>
            <th>Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byType as $type => $amount)
            <tr>
                <td>{{ ucfirst($type) }}</td>
                <td>{{ number_format($amount, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Payment Methods</th>
        </tr>
        <tr>
            <th>Method</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paymentMethods as $method => $amount)
            <tr>
                <td>{{ ucfirst($method) }}</td>
                <td>{{ number_format($amount, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
