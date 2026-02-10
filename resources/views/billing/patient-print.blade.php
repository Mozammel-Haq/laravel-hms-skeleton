<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; background: #f9f9f9; }
        .invoice-box { max-width: 800px; margin: auto; background: #fff; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .company-info h2 { margin: 0; color: #2d3748; }
        .invoice-info h3 { margin: 0; color: #718096; }
        .details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f7fafc; color: #4a5568; font-weight: 600; text-align: left; padding: 12px; }
        td { padding: 12px; border-bottom: 1px solid #edf2f7; color: #4a5568; }
        .amounts { text-align: right; margin-left: auto; width: 300px; }
        .amount-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .amount-row.total { font-weight: bold; font-size: 1.2em; border-top: 2px solid #eee; margin-top: 10px; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 999px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #822727; }
        .status-partial { background: #feebc8; color: #744210; }

        @media print {
            .no-print { display: none; }
            body { background: #fff; padding: 0; }
            .invoice-box { box-shadow: none; padding: 0; max-width: none; }
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h2>{{ config('app.name') }}</h2>
                <p>123 Hospital Way<br>Medical District, City<br>Phone: (555) 123-4567</p>
            </div>
            <div class="invoice-info" style="text-align: right;">
                <h3>INVOICE</h3>
                <p><strong>#{{ $invoice->invoice_number }}</strong></p>
                <p>{{ $invoice->issued_at ? $invoice->issued_at->format('M d, Y') : $invoice->created_at->format('M d, Y') }}</p>
                <span class="status-badge status-{{ strtolower($invoice->status) }}">{{ $invoice->status }}</span>
            </div>
        </div>

        <div class="details">
            <h4>Bill To:</h4>
            <p><strong>{{ $invoice->patient->name }}</strong><br>
            ID: {{ $invoice->patient->patient_code }}<br>
            {{ $invoice->patient->phone }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="amounts">
            <div class="amount-row">
                <span>Subtotal:</span>
                <span>{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
            <div class="amount-row">
                <span>Discount:</span>
                <span>-{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            @if($invoice->tax > 0)
            <div class="amount-row">
                <span>Tax:</span>
                <span>+{{ number_format($invoice->tax, 2) }}</span>
            </div>
            @endif
            <div class="amount-row total">
                <span>Total:</span>
                <span>{{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>

        @if($invoice->payments->count() > 0)
        <div style="margin-top: 40px;">
            <h4>Payment History</h4>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : ($payment->created_at ? $payment->created_at->format('M d, Y') : '-') }}</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="no-print" style="margin-top: 40px; text-align: center;">
            <button onclick="window.print()" style="padding: 12px 24px; background: #2d3748; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">Print Invoice</button>
            <button onclick="window.close()" style="padding: 12px 24px; background: #e2e8f0; color: #4a5568; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-left: 10px;">Close</button>
        </div>
    </div>
</body>
</html>
