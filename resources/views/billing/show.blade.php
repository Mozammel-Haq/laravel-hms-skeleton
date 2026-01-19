<x-app-layout>
    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .pos-receipt,
                .pos-receipt * {
                    visibility: visible;
                }

                .pos-receipt {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    padding: 10px;
                    display: block !important;
                }

                .no-print {
                    display: none !important;
                }

                /* Hide Sidebar, Header, etc. if they are not covered by body * visibility hidden */
                .sidebar,
                .navbar,
                .footer {
                    display: none !important;
                }

                .page-wrapper {
                    margin: 0 !important;
                    padding: 0 !important;
                }
            }

            .pos-receipt {
                display: none;
                width: 80mm;
                /* Standard Thermal Paper Width */
                max-width: 80mm;
                background: #fff;
                padding: 5mm;
                /* Reduced padding for better fit on 80mm paper */
                margin: 0 auto;
                /* Center on page if printed on A4, usually irrelevant for POS printers */
                font-family: 'Courier New', Courier, monospace;
                color: #000;
            }

            .pos-receipt .dashed-border {
                border-bottom: 1px dashed #000;
                margin: 8px 0;
            }

            .pos-receipt h4 {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 5px;
                color: #000;
            }

            .pos-receipt p {
                font-size: 12px;
                margin-bottom: 3px;
                text-align: center;
                color: #000;
            }

            .pos-receipt .info-row {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 3px;
            }

            .pos-receipt table {
                width: 100%;
                font-size: 12px;
                border-collapse: collapse;
            }

            .pos-receipt th {
                text-align: left;
                border-bottom: 1px dashed #000;
                padding: 5px 0;
            }

            .pos-receipt td {
                padding: 5px 0;
                vertical-align: top;
            }

            .pos-receipt .text-end {
                text-align: right;
            }

            .pos-receipt .total-row {
                font-weight: bold;
                font-size: 14px;
            }
        </style>
    @endpush

    <div class="container-fluid mx-2 no-print">

        <div class="row">
            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="page-title mb-0">Invoice {{ $invoice->invoice_number }}</h3>
                            <div>
                                <button onclick="window.print()" class="btn btn-outline-primary me-2">
                                    <i class="ti ti-printer me-1"></i> Print
                                </button>
                                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Back</a>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong>Patient:</strong> {{ optional($invoice->patient)->name ?? 'Patient' }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($invoice->status) }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal</th>
                                        <th>{{ number_format($invoice->subtotal, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Discount</th>
                                        <th>{{ number_format($invoice->discount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Tax</th>
                                        <th>{{ number_format($invoice->tax, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th>{{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Payments</h5>
                        <ul class="list-group">
                            @forelse ($invoice->payments as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i') }}
                                    ({{ $p->payment_method }})
                                    <span>{{ number_format($p->amount, 2) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">No payments yet.</li>
                            @endforelse
                        </ul>

                        @php
                            $totalPaid = $invoice->payments->sum('amount');
                            $remaining = $invoice->total_amount - $totalPaid;
                        @endphp

                        @if ($invoice->status !== 'paid' && $remaining > 0)
                            <div class="mt-3">
                                <a href="{{ route('billing.payment.add', $invoice->id) }}"
                                    class="btn btn-primary w-100">Add Payment</a>
                            </div>
                        @else
                            <div class="mt-3 alert alert-success text-center mb-0">
                                <i class="ti ti-check-circle me-1"></i> Paid in Full
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POS Receipt Layout -->
    <div class="pos-receipt">
        <h4>{{ $invoice->clinic?->name ?? 'Clinic Name' }}</h4>
        <p>
            {{ $invoice->clinic?->address_line_1 ?? '' }}
            @if ($invoice->clinic?->address_line_2)
                , {{ $invoice->clinic->address_line_2 }}
            @endif
        </p>
        <p>
            {{ $invoice->clinic?->city ?? '' }}
            @if ($invoice->clinic?->postal_code)
                - {{ $invoice->clinic->postal_code }}
            @endif
        </p>
        <p>Tel: {{ $invoice->clinic?->phone ?? 'N/A' }}</p>

        <div class="dashed-border"></div>

        <div class="info-row">
            <span>Date: {{ $invoice->created_at }}</span>
            <span>Inv: {{ $invoice->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span>Patient: {{ optional($invoice->patient)->name ?? 'Walk-in' }}</span>
        </div>
        @if (optional($invoice->patient)->phone)
            <div class="info-row">
                <span>Ph: {{ $invoice->patient->phone }}</span>
            </div>
        @endif
        @if (optional($invoice->patient)->address)
            <div class="info-row">
                <span>Addr: {{ $invoice->patient->address }}</span>
            </div>
        @endif

        <div class="dashed-border"></div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-start">Qty</th>
                    <th class="text-end">Amt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-start">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="dashed-border"></div>

        <div class="info-row">
            <span>Subtotal</span>
            <span>{{ number_format($invoice->subtotal) }}</span>
        </div>
        @if ($invoice->discount > 0)
            <div class="info-row">
                <span>Discount</span>
                <span>-{{ number_format($invoice->discount) }}</span>
            </div>
        @endif
        @if ($invoice->tax > 0)
            <div class="info-row">
                <span>Tax</span>
                <span>{{ number_format($invoice->tax) }}</span>
            </div>
        @endif

        <div class="dashed-border"></div>

        <div class="info-row total-row">
            <span>TOTAL</span>
            <span>{{ number_format($invoice->total_amount) }}</span>
        </div>

        <div class="info-row">
            <span>Paid</span>
            <span>{{ number_format($invoice->payments->sum('amount')) }}</span>
        </div>
        <div class="info-row">
            <span>Due</span>
            <span>{{ number_format($invoice->total_amount - $invoice->payments->sum('amount')) }}</span>
        </div>

        <div class="dashed-border"></div>

        <p style="margin-top: 10px;">Thank you for your visit!</p>
        <p>Get Well Soon</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (new URLSearchParams(window.location.search).has('print')) {
                window.print();
            }
        });
    </script>
</x-app-layout>
