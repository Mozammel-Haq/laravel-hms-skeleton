<x-app-layout>
    @push('styles')
        <style>
            /* Base KPI Card Styles */
            .kpi-card {
                border: 1.5px solid;
                transition: all 0.3s ease;
            }

            /* Light Mode Backgrounds */
            .kpi-primary {
                background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
                border-color: #c2d9ff !important;
            }

            .kpi-success {
                background: linear-gradient(135deg, #f0f9f5 0%, #e6f4ec 100%);
                border-color: #b8e6cf !important;
            }

            .kpi-warning {
                background: linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%);
                border-color: #ffe5b6 !important;
            }

            .kpi-danger {
                background: linear-gradient(135deg, #fff0f0 0%, #ffe6e6 100%);
                border-color: #ffc2c2 !important;
            }

            .kpi-info {
                background: linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%);
                border-color: #b6e7f2 !important;
            }

            /* Dark Mode Backgrounds */
            [data-bs-theme="dark"] .kpi-primary {
                background: linear-gradient(135deg, #0d2b5c 0%, #0a1f42 100%) !important;
                border-color: #1e4b9e !important;
            }

            [data-bs-theme="dark"] .kpi-success {
                background: linear-gradient(135deg, #0d4229 0%, #0a2e1d 100%) !important;
                border-color: #157347 !important;
            }

            [data-bs-theme="dark"] .kpi-warning {
                background: linear-gradient(135deg, #664d03 0%, #4d3a02 100%) !important;
                border-color: #996c00 !important;
            }

            [data-bs-theme="dark"] .kpi-danger {
                background: linear-gradient(135deg, #5c0d0d 0%, #420a0a 100%) !important;
                border-color: #9e1e1e !important;
            }

            [data-bs-theme="dark"] .kpi-info {
                background: linear-gradient(135deg, #0a3d4c 0%, #072a35 100%) !important;
                border-color: #166d84 !important;
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
                background: rgba(13, 110, 253, 0.1) !important;
                border: 1px solid rgba(13, 110, 253, 0.2) !important;
            }

            .kpi-success .kpi-icon-container {
                background: rgba(25, 135, 84, 0.1) !important;
                border: 1px solid rgba(25, 135, 84, 0.2) !important;
            }

            .kpi-warning .kpi-icon-container {
                background: rgba(255, 193, 7, 0.1) !important;
                border: 1px solid rgba(255, 193, 7, 0.2) !important;
            }

            .kpi-danger .kpi-icon-container {
                background: rgba(220, 53, 69, 0.1) !important;
                border: 1px solid rgba(220, 53, 69, 0.2) !important;
            }

            .kpi-info .kpi-icon-container {
                background: rgba(23, 162, 184, 0.1) !important;
                border: 1px solid rgba(23, 162, 184, 0.2) !important;
            }

            /* Icon Container - dark mode */
            [data-bs-theme="dark"] .kpi-icon-container {
                background: rgba(110, 168, 254, 0.1) !important;
                border: 1px solid rgba(110, 168, 254, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-success .kpi-icon-container {
                background: rgba(117, 183, 152, 0.1) !important;
                border: 1px solid rgba(117, 183, 152, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-warning .kpi-icon-container {
                background: rgba(255, 218, 106, 0.1) !important;
                border: 1px solid rgba(255, 218, 106, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-danger .kpi-icon-container {
                background: rgba(220, 53, 69, 0.1) !important;
                border: 1px solid rgba(220, 53, 69, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-info .kpi-icon-container {
                background: rgba(110, 223, 246, 0.1) !important;
                border: 1px solid rgba(110, 223, 246, 0.2) !important;
            }

            /* Footer Text */
            .kpi-footer {
                color: var(--bs-secondary-color) !important;
                margin-bottom: 0;
            }
        </style>
    @endpush
    <div class="container-fluid mx-2">
        <!-- Filter & Header -->
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h3 class="page-title mb-1">Pharmacy Profit Report</h3>
                        <p class="text-primary fw-semibold mb-0">
                            Period: {{ $startDate->format('M d, Y') }} -
                            {{ $endDate->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}"
                            class="btn btn-success d-print-none">
                            <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-primary d-print-none">
                            <i class="ti ti-printer me-1"></i> Print / PDF
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary d-print-none">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <form method="get" action="{{ route('reports.pharmacy_profit') }}" class="d-print-none">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" name="range" value="today"
                                    class="btn btn-outline-primary {{ request('range') == 'today' ? 'active' : '' }}">Today</button>
                                <button type="submit" name="range" value="week"
                                    class="btn btn-outline-primary {{ request('range') == 'week' ? 'active' : '' }}">Week</button>
                                <button type="submit" name="range" value="month"
                                    class="btn btn-outline-primary {{ request('range', 'month') == 'month' ? 'active' : '' }}">Month</button>
                                <button type="submit" name="range" value="year"
                                    class="btn btn-outline-primary {{ request('range') == 'year' ? 'active' : '' }}">Year</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" placeholder="Start Date"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" placeholder="End Date"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="range" value="custom"
                                class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card p-3 mb-2">
            <!-- KPI Cards -->
            <div class="row g-4 mb-4">
                <!-- Total Revenue Card -->
                <div class="col-md-3">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-revenue" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                            fill-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-revenue)" />
                            </svg>
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL SALES</h6>
                                    <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($totalRevenue, 2) }}</h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <i class="ti ti-currency-taka fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Cost Card -->
                <div class="col-md-3">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-warning"
                        data-bs-theme="light,dark">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                             <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-cost" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <rect x="0" y="0" width="2" height="2" fill="var(--bs-warning)" fill-opacity="0.2"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-cost)" />
                            </svg>
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL COST</h6>
                                    <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($totalCost, 2) }}</h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <i class="ti ti-shopping-cart fs-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Net Profit Card -->
                <div class="col-md-3">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card {{ $netProfit >= 0 ? 'kpi-success' : 'kpi-danger' }}"
                        data-bs-theme="light,dark">
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                             <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-profit" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <path d="M0 40L40 0H20L0 20M40 40V20L20 40" stroke="{{ $netProfit >= 0 ? 'var(--bs-success)' : 'var(--bs-danger)' }}" stroke-width="2" fill="none" stroke-opacity="0.1"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-profit)" />
                            </svg>
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">NET PROFIT</h6>
                                    <h2 class="fw-bold kpi-value mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                        ৳{{ number_format($netProfit, 2) }}
                                    </h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <i class="ti ti-trending-{{ $netProfit >= 0 ? 'up' : 'down' }} fs-2 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Margin Card -->
                <div class="col-md-3">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                        data-bs-theme="light,dark">
                         <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                             <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-margin" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <circle cx="2" cy="2" r="2" fill="var(--bs-info)" fill-opacity="0.2"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-margin)" />
                            </svg>
                        </div>
                        <div class="card-body position-relative z-1 p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">PROFIT MARGIN</h6>
                                    <h2 class="fw-bold kpi-value mb-0">
                                        {{ $totalRevenue > 0 ? number_format(($netProfit / $totalRevenue) * 100, 1) : 0 }}%
                                    </h2>
                                </div>
                                <div class="rounded-3 p-2 kpi-icon-container">
                                    <i class="ti ti-chart-pie fs-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Table -->
            <div class="card border-0 shadow-none">
                <div class="card-header bg-transparent border-0 px-0">
                    <h5 class="card-title mb-0">Detailed Sales Report</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Medicine</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Cost</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total Cost</th>
                                <th class="text-end">Total Sales</th>
                                <th class="text-end">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($saleItems as $item)
                                @php
                                    $cost = $item->quantity * $item->unit_cost;
                                    $sales = $item->quantity * $item->unit_price;
                                    $profit = $sales - $cost;
                                @endphp
                                <tr>
                                    <td>{{ $item->pharmacySale->sale_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->medicine->name }}</div>
                                        <small class="text-muted">{{ $item->medicine->generic_name }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">৳{{ number_format($item->unit_cost, 2) }}</td>
                                    <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">৳{{ number_format($cost, 2) }}</td>
                                    <td class="text-end">৳{{ number_format($sales, 2) }}</td>
                                    <td class="text-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                        ৳{{ number_format($profit, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <img src="{{ asset('assets/img/no-data.svg') }}" alt="No Data" class="mb-3" style="width: 100px; opacity: 0.5;">
                                        <p class="text-muted mb-0">No pharmacy sales found for this period.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $saleItems->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
