<x-app-layout>
    @push('styles')
        <style>
            /* Remove hover effect for these KPI cards */
            .kpi-card {
                transition: all 0.3s ease;
                border: 1.5px solid;
            }

            /* Or if you want to completely disable hover effects */
            .kpi-card:hover {
                transform: none;
            }

            /* Ensure CSS variables are defined */
            :root {
                --primary-color: #0d6efd;
                --danger-color: #dc3545;
                --secondary-color: #6c757d;
            }

            [data-bs-theme="dark"] {
                --primary-color: #6ea8fe;
                --danger-color: #e6858d;
                --secondary-color: #adb5bd;
            }

            /* Light mode backgrounds */
            .kpi-secondary {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-color: #dee2e6 !important;
            }

            .kpi-danger {
                background: linear-gradient(135deg, #fdf0f0 0%, #fce8e8 100%);
                border-color: #f5c6cb !important;
            }

            .kpi-primary {
                background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
                border-color: #c2d9ff !important;
            }

            /* Dark mode backgrounds */
            [data-bs-theme="dark"] .kpi-secondary {
                background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
                border-color: #6c757d !important;
            }

            [data-bs-theme="dark"] .kpi-danger {
                background: linear-gradient(135deg, #5c1a1a 0%, #421414 100%) !important;
                border-color: #dc3545 !important;
            }

            [data-bs-theme="dark"] .kpi-primary {
                background: linear-gradient(135deg, #0d2b5c 0%, #0a1f42 100%) !important;
                border-color: #1e4b9e !important;
            }

            /* Pattern opacity adjustment for dark mode */
            [data-bs-theme="dark"] .pattern-bg {
                opacity: 0.15 !important;
            }

            [data-bs-theme="dark"] .decorative-shape {
                opacity: 0.1 !important;
            }

            /* KPI Label */
            .kpi-label {
                color: var(--bs-secondary-color) !important;
            }

            /* KPI Value */
            .kpi-value {
                color: var(--bs-body-color) !important;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
            }

            [data-bs-theme="dark"] .kpi-value {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }

            /* Icon Container - light mode */
            .kpi-icon-container {
                background: rgba(108, 117, 125, 0.1) !important;
                border: 1px solid rgba(108, 117, 125, 0.2) !important;
            }

            .kpi-danger .kpi-icon-container {
                background: rgba(220, 53, 69, 0.1) !important;
                border: 1px solid rgba(220, 53, 69, 0.2) !important;
            }

            .kpi-primary .kpi-icon-container {
                background: rgba(13, 110, 253, 0.1) !important;
                border: 1px solid rgba(13, 110, 253, 0.2) !important;
            }

            /* Icon Container - dark mode */
            [data-bs-theme="dark"] .kpi-icon-container {
                background: rgba(173, 181, 189, 0.1) !important;
                border: 1px solid rgba(173, 181, 189, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-danger .kpi-icon-container {
                background: rgba(230, 133, 141, 0.1) !important;
                border: 1px solid rgba(230, 133, 141, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-primary .kpi-icon-container {
                background: rgba(110, 168, 254, 0.1) !important;
                border: 1px solid rgba(110, 168, 254, 0.2) !important;
            }

            /* Small Icon - light mode */
            .kpi-small-icon {
                border-color: rgba(108, 117, 125, 0.3) !important;
                background-color: var(--bs-body-bg) !important;
            }

            .kpi-danger .kpi-small-icon {
                border-color: rgba(220, 53, 69, 0.3) !important;
            }

            .kpi-primary .kpi-small-icon {
                border-color: rgba(13, 110, 253, 0.3) !important;
            }

            /* Small Icon - dark mode */
            [data-bs-theme="dark"] .kpi-small-icon {
                border-color: rgba(173, 181, 189, 0.3) !important;
            }

            [data-bs-theme="dark"] .kpi-danger .kpi-small-icon {
                border-color: rgba(230, 133, 141, 0.3) !important;
            }

            [data-bs-theme="dark"] .kpi-primary .kpi-small-icon {
                border-color: rgba(110, 168, 254, 0.3) !important;
            }

            /* Divider - light mode */
            .kpi-divider {
                border-color: rgba(108, 117, 125, 0.2) !important;
            }

            .kpi-danger .kpi-divider {
                border-color: rgba(220, 53, 69, 0.2) !important;
            }

            .kpi-primary .kpi-divider {
                border-color: rgba(13, 110, 253, 0.2) !important;
            }

            /* Divider - dark mode */
            [data-bs-theme="dark"] .kpi-divider {
                border-color: rgba(173, 181, 189, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-danger .kpi-divider {
                border-color: rgba(230, 133, 141, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-primary .kpi-divider {
                border-color: rgba(110, 168, 254, 0.2) !important;
            }

            /* Footer Text */
            .kpi-footer {
                color: var(--bs-secondary-color) !important;
                margin-bottom: 0;
            }
        </style>
    @endpush
    <div class="container-fluid mt-3">
        <!-- Header & Controls -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="page-title">Tax Report</h3>
            </div>
            <div class="col-md-6 text-end d-print-none">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" class="btn btn-success me-2">
                    <i class="bi bi-file-spreadsheet"></i> Export Excel
                </a>
                <div class="btn-group" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['range' => 'today']) }}"
                        class="btn btn-outline-primary {{ request('range') == 'today' ? 'active' : '' }}">Today</a>
                    <a href="{{ request()->fullUrlWithQuery(['range' => 'week']) }}"
                        class="btn btn-outline-primary {{ request('range') == 'week' ? 'active' : '' }}">Week</a>
                    <a href="{{ request()->fullUrlWithQuery(['range' => 'month', 'start_date' => null, 'end_date' => null]) }}"
                        class="btn btn-outline-primary {{ request('range', 'month') == 'month' && !request('start_date') ? 'active' : '' }}">Month</a>
                    <a href="{{ request()->fullUrlWithQuery(['range' => 'year']) }}"
                        class="btn btn-outline-primary {{ request('range') == 'year' ? 'active' : '' }}">Year</a>
                </div>
                <button class="btn btn-outline-primary ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#customDateRange">
                    Custom
                </button>
            </div>
        </div>

        <!-- Custom Date Range Form -->
        <div class="collapse mb-4 {{ request('range') == 'custom' ? 'show' : '' }}" id="customDateRange">
            <div class="card card-body">
                <form action="{{ route('reports.tax') }}" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="range" value="custom">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Apply Range</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Info -->
        <div class="alert border-start-4 border-start-primary bg-white rounded-4 mb-5"
            style="border-left-width: 4px!important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-calendar-check text-primary fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-semibold text-primary mb-1">Report Period</h6>
                    <p class="mb-0 ">
                        Showing data from <span
                            class="fw-semibold ttext-primary">{{ $startDate->format('M d, Y') }}</span>
                        to <span class="fw-semibold text-primary">{{ $endDate->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>
        </div>

        @php
            $taxableAmount = $invoices->sum('total_amount'); // Base Amount
            $taxAmount = $totalTax; // Calculated in controller
            $grandTotal = $taxableAmount + $taxAmount;
        @endphp

        <!-- KPIs -->
        <div class="row mb-5 g-4">
            <!-- Taxable Amount Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-secondary"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-taxable" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--secondary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-taxable)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--secondary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TAXABLE
                                    AMOUNT</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳ {{ number_format($taxableAmount, 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 7V3H16V7" stroke="var(--secondary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M16 21V17H8V21" stroke="var(--secondary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M18 21H6C4.89543 21 4 20.1046 4 19V5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21Z"
                                        stroke="var(--secondary-color)" stroke-width="1.5" />
                                    <path d="M12 9V15" stroke="var(--secondary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 17H15" stroke="var(--secondary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M9 13H15" stroke="var(--secondary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path
                                            d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                            stroke="var(--secondary-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Total Invoice Value (Excl. Tax)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Collected Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-danger"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-dots-taxcollected" x="0" y="0" width="30" height="30"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--danger-color)"
                                        fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-dots-taxcollected)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                        style="background: radial-gradient(circle at bottom left, var(--danger-color) 0%, transparent 70%); opacity: 0.1;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TAX
                                    COLLECTED (10%)</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳ {{ number_format($taxAmount, 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z"
                                        stroke="var(--danger-color)" stroke-width="1.5" />
                                    <path d="M9 7H15" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 11H15" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 15H12" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M7 7V21" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8 7H16C18.7614 7 21 9.23858 21 12C21 14.7614 18.7614 17 16 17H8C5.23858 17 3 14.7614 3 12C3 9.23858 5.23858 7 8 7Z"
                                            stroke="var(--danger-color)" stroke-width="1.5" />
                                        <path d="M12 8V12" stroke="var(--danger-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Total VAT/Tax</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-lines-taxrevenue" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <line x1="0" y1="40" x2="40" y2="0"
                                        stroke="var(--primary-color)" stroke-width="1" stroke-opacity="0.2" />
                                    <line x1="0" y1="0" x2="40" y2="40"
                                        stroke="var(--primary-color)" stroke-width="1" stroke-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-lines-taxrevenue)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                        style="background: radial-gradient(circle at center, var(--primary-color) 0%, transparent 70%); opacity: 0.08;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    REVENUE</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳ {{ number_format($grandTotal, 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 6V12L15 15" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 17H15" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M9 13H15" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path
                                            d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                            stroke="var(--primary-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Incl. Tax</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Tax Trend -->
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Tax Collection Trend</h5>
                    </div>
                    <div class="card-body">
                        <div id="taxTrendChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0">Tax Breakdown by Invoice</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th class="text-end">Taxable Amount</th>
                                <th class="text-end">Tax (10%)</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                @php
                                    $subtotal = $invoice->total_amount;
                                    $vat = $subtotal * 0.1;
                                    $total = $subtotal + $vat;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="#"
                                            class="text-decoration-none fw-bold">#{{ $invoice->id }}</a>
                                        <div class="small text-muted">{{ ucfirst($invoice->status) }}</div>
                                    </td>
                                    <td>{{ $invoice->created_at }}</td>
                                    <td class="text-end">৳{{ number_format($subtotal, 2) }}</td>
                                    <td class="text-end text-danger">+ ৳{{ number_format($vat, 2) }}</td>
                                    <td class="text-end fw-bold">৳{{ number_format($total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No invoices found in this
                                        period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Tax Trend Chart
            var taxOptions = {
                series: [{
                    name: 'Tax Collected',
                    data: @json($taxTrend->pluck('tax'))
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight',
                    width: 2
                },
                xaxis: {
                    categories: @json($taxTrend->pluck('date')),
                    type: 'category'
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return "$" + value.toFixed(0);
                        }
                    }
                },
                colors: ['#dc3545'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                }
            };
            new ApexCharts(document.querySelector("#taxTrendChart"), taxOptions).render();
        </script>
    @endpush
</x-app-layout>
