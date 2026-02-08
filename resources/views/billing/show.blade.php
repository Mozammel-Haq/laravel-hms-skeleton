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

                /* Hide sorting icons in print */
                th.sorting::before,
                th.sorting::after,
                th.sorting_asc::before,
                th.sorting_asc::after,
                th.sorting_desc::before,
                th.sorting_desc::after {
                    display: none !important;
                    content: none !important;
                }

                th.sorting,
                th.sorting_asc,
                th.sorting_desc {
                    background-image: none !important;
                    padding-right: initial !important;
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

    <div class="container-fluid mx-2 mt-2 no-print">
        <div class="card p-3">
            <!-- Payment Status Alerts -->
            @if(request('payment_status') == 'success')
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    <strong>Success!</strong> {{ request('message', 'Payment successful!') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(request('payment_status') == 'error')
                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
                    <strong>Error!</strong> {{ request('message', 'Payment failed.') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Breadcrumbs & Header -->
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top mb-0">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Invoice Details</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $invoice->invoice_number }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-sm btn-secondary">
                        <i class="ti ti-printer me-1"></i> Print Receipt
                    </button>
                    <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-file-invoice me-2 text-primary"></i> Invoice #{{ $invoice->invoice_number }}
                        </h5>
                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Invoice Meta -->
                        <div class="mb-4 p-3 bg-light rounded border border-secondary-subtle">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1">Patient</small>
                                    <div class="fw-bold text-dark fs-6">{{ optional($invoice->patient)->name ?? 'Walk-in Patient' }}</div>
                                    @if(optional($invoice->patient)->patient_code)
                                        <div class="text-muted small">{{ $invoice->patient->patient_code }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1">Date</small>
                                    <div class="fw-bold text-dark fs-6">{{ $invoice->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 border-top">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4">Description</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td class="ps-4">{{ $item->description }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">৳ {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end pe-4 fw-bold">৳ {{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="3" class="text-end pt-3 text-muted">Subtotal</td>
                                        <td class="text-end pe-4 pt-3 fw-bold">৳ {{ number_format($invoice->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Discount</td>
                                        <td class="text-end pe-4 text-danger">-৳ {{ number_format($invoice->discount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Tax</td>
                                        <td class="text-end pe-4">৳ {{ number_format($invoice->tax, 2) }}</td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end py-3 fw-bold text-dark">Total Amount</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-primary fs-5">৳ {{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-credit-card me-2 text-primary"></i> Payment History
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($invoice->payments as $p)
                                <li class="list-group-item px-4 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold {{ $p->status === 'failed' ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                                            ৳ {{ number_format($p->amount, 2) }}
                                        </span>
                                        <div>
                                            @if($p->status === 'success')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle me-1">Paid</span>
                                            @elseif($p->status === 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle me-1">Pending</span>
                                            @elseif($p->status === 'failed')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle me-1">Failed</span>
                                            @endif
                                            <span class="badge bg-light text-dark border">{{ $p->payment_method }}</span>
                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="ti ti-calendar-time me-1"></i>
                                        {{ \Carbon\Carbon::parse($p->paid_at)->format('d M Y, h:i A') }}
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item px-4 py-4 text-center text-muted">
                                    <i class="ti ti-credit-card-off fs-1 mb-2 opacity-50"></i>
                                    <p class="mb-0">No payments recorded.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-light p-4">
                        @php
                            $totalPaid = $invoice->payments->where('status', 'success')->sum('amount');
                            $remaining = max($invoice->total_amount - $totalPaid, 0);
                        @endphp

                        @if ($invoice->status !== 'paid' && $remaining > 0)
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-bold">Remaining Due:</span>
                                <span class="text-danger fw-bold fs-5">৳ {{ number_format($remaining, 2) }}</span>
                            </div>
                            <a href="{{ route('billing.payment.add', $invoice->id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="ti ti-plus me-1"></i> Add Manual Payment
                            </a>

                            <!-- Online Payment -->
                            <form action="{{ route('online-payment.initiate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="invoice">
                                <input type="hidden" name="id" value="{{ $invoice->id }}">
                                <input type="hidden" name="amount" value="{{ $remaining }}">

                                <div class="mb-2">
                                    <select name="gateway" class="form-select form-select-sm">
                                        <option value="stripe">Stripe</option>
                                        <option value="sslcommerz">SSLCommerz</option>
                                    </select>
                                </div>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="ti ti-credit-card me-1"></i> Pay Online ({{ number_format($remaining, 2) }})
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success text-center mb-0 border-success-subtle">
                                <i class="ti ti-check-circle me-1 fs-5 align-middle"></i> <span class="align-middle fw-bold">Paid in Full</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POS Receipt Layout -->
    <div class="pos-receipt">
        @if ($invoice->clinic?->logo_path)
            <div style="text-align: center; margin-bottom: 10px;">
                <img src="{{ asset("/") }}/{{ Storage::url($invoice->clinic->logo_path) }}"
                    style="max-height: 60px; max-width: 100%; object-fit: contain;">
            </div>
        @endif
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

        <table class="static-table">
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
