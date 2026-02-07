<table>
    <thead>
    <tr>
        <th colspan="9" style="font-weight: bold; font-size: 16px; text-align: center;">Pharmacy Profit Report</th>
    </tr>
    <tr>
        <th colspan="9" style="text-align: center;">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</th>
    </tr>
    <tr>
        <th colspan="9"></th>
    </tr>
    <tr>
        <th colspan="3">Total Revenue: {{ number_format($totalRevenue, 2) }}</th>
        <th colspan="3">Total Cost: {{ number_format($totalCost, 2) }}</th>
        <th colspan="3">Net Profit: {{ number_format($netProfit, 2) }}</th>
    </tr>
    <tr>
        <th colspan="9"></th>
    </tr>
    <tr>
        <th style="font-weight: bold;">Date</th>
        <th style="font-weight: bold;">Medicine</th>
        <th style="font-weight: bold;">Generic Name</th>
        <th style="font-weight: bold;">Quantity</th>
        <th style="font-weight: bold;">Unit Cost</th>
        <th style="font-weight: bold;">Unit Price</th>
        <th style="font-weight: bold;">Total Cost</th>
        <th style="font-weight: bold;">Total Sales</th>
        <th style="font-weight: bold;">Profit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($saleItems as $item)
        @php
            $cost = $item->quantity * $item->unit_cost;
            $sales = $item->quantity * $item->unit_price;
            $profit = $sales - $cost;
        @endphp
        <tr>
            <td>{{ $item->pharmacySale->sale_date->format('Y-m-d H:i') }}</td>
            <td>{{ $item->medicine->name ?? 'N/A' }}</td>
            <td>{{ $item->medicine->generic_name ?? 'N/A' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->unit_cost }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $cost }}</td>
            <td>{{ $sales }}</td>
            <td>{{ $profit }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
