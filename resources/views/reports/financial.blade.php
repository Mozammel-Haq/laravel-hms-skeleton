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

            [data-bs-theme="dark"] .kpi-info .kpi-icon-container {
                background: rgba(110, 223, 246, 0.1) !important;
                border: 1px solid rgba(110, 223, 246, 0.2) !important;
            }

            /* Small Icon - light mode */
            .kpi-small-icon {
                border-color: rgba(13, 110, 253, 0.3) !important;
                background-color: var(--bs-body-bg) !important;
            }

            .kpi-success .kpi-small-icon {
                border-color: rgba(25, 135, 84, 0.3) !important;
            }

            .kpi-warning .kpi-small-icon {
                border-color: rgba(255, 193, 7, 0.3) !important;
            }

            .kpi-info .kpi-small-icon {
                border-color: rgba(23, 162, 184, 0.3) !important;
            }

            /* Small Icon - dark mode */
            [data-bs-theme="dark"] .kpi-small-icon {
                border-color: rgba(110, 168, 254, 0.3) !important;
            }

            [data-bs-theme="dark"] .kpi-success .kpi-small-icon {
                border-color: rgba(117, 183, 152, 0.3) !important;
            }

            [data-bs-theme="dark"] .kpi-warning .kpi-small-icon {
                border-color: rgba(255, 218, 106, 0.3) !important;
            }

            [data-bs-theme="dark"] .kpi-info .kpi-small-icon {
                border-color: rgba(110, 223, 246, 0.3) !important;
            }

            /* Divider - light mode */
            .kpi-divider {
                border-color: rgba(13, 110, 253, 0.2) !important;
            }

            .kpi-success .kpi-divider {
                border-color: rgba(25, 135, 84, 0.2) !important;
            }

            .kpi-warning .kpi-divider {
                border-color: rgba(255, 193, 7, 0.2) !important;
            }

            .kpi-info .kpi-divider {
                border-color: rgba(23, 162, 184, 0.2) !important;
            }

            /* Divider - dark mode */
            [data-bs-theme="dark"] .kpi-divider {
                border-color: rgba(110, 168, 254, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-success .kpi-divider {
                border-color: rgba(117, 183, 152, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-warning .kpi-divider {
                border-color: rgba(255, 218, 106, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-info .kpi-divider {
                border-color: rgba(110, 223, 246, 0.2) !important;
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
                        <h3 class="page-title mb-1">Financial Report</h3>
                        <p class="text-primary fw-semibold mb-0">
                            Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}"
                            class="btn btn-success d-print-none">
                            <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary d-print-none">
                            <i class="ti ti-printer me-1"></i> Print / PDF
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary d-print-none">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <form method="get" action="{{ route('reports.financial') }}" class="d-print-none">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
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
                            <select name="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                                </option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                                </option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial
                                </option>
                            </select>
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

        <!-- KPI Cards -->
        <div class="row mb-5 g-4">
            <!-- Total Revenue Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-5" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-5)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    REVENUE</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($revenue, 2) }}</h2>
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
                                <p class="text-muted kpi-footer">All invoiced amounts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collected (Paid) Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-dots-6" x="0" y="0" width="30" height="30"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--success-color)"
                                        fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-dots-6)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                        style="background: radial-gradient(circle at bottom left, var(--success-color) 0%, transparent 70%); opacity: 0.1;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    COLLECTED (PAID)</h6>
                                <h2 class="fw-bold kpi-value text-success mb-0">৳{{ number_format($paid, 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12L11 14L15 10" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                        stroke="var(--success-color)" stroke-width="1.5" />
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
                                            stroke="var(--success-color)" stroke-width="1.5" />
                                        <path d="M12 8V12" stroke="var(--success-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Received payments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending (Unpaid) Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-warning"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-lines-7" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <line x1="0" y1="40" x2="40" y2="0"
                                        stroke="var(--warning-color)" stroke-width="1" stroke-opacity="0.2" />
                                    <line x1="0" y1="0" x2="40" y2="40"
                                        stroke="var(--warning-color)" stroke-width="1" stroke-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-lines-7)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                        style="background: radial-gradient(circle at center, var(--warning-color) 0%, transparent 70%); opacity: 0.08;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">PENDING
                                    (UNPAID)</h6>
                                <h2 class="fw-bold kpi-value text-warning mb-0">৳ {{ number_format($pending, 2) }}
                                </h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 8V12L15 15" stroke="var(--warning-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                        stroke="var(--warning-color)" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V12L15 15" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 2V6" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 18V22" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Awaiting collection</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Invoices Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-diagonal-8" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <path d="M0 20L20 0" stroke="var(--info-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M-10 10L10 -10" stroke="var(--info-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M10 30L30 10" stroke="var(--info-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-diagonal-8)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-0 start-0 w-25 h-25 decorative-shape">
                        <svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0,0 L100,0 L0,100 Z" fill="var(--info-color)" fill-opacity="0.1" />
                        </svg>
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    INVOICES</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ number_format($invoiceCount) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z"
                                        stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14 2V8H20" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16 13H8" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M16 17H8" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M10 9H9H8" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 7V3H16V7" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M16 21V17H8V21" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path
                                            d="M18 21H6C4.89543 21 4 20.1046 4 19V5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21Z"
                                            stroke="var(--info-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Issued documents</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Charts Row 1 -->
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Revenue Trend</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenueTrendChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Revenue by Source</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenueSourceChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Payment Methods</h5>
                    </div>
                    <div class="card-body">
                        <div id="paymentMethodsChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Daily Breakdown</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($daily as $day)
                                        <tr>
                                            <td>{{ $day->date }}</td>
                                            <td class="text-end fw-semibold">৳ {{ number_format($day->total, 2) }}
                                            </td>
                                            <td class="text-end text-success">
                                                ৳ {{ number_format($day->paid_amount, 2) }}</td>
                                            <td class="text-end text-danger">
                                                ৳ {{ number_format($day->total - $day->paid_amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">No data available
                                                for this period</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Revenue Trend Chart
                var trendOptions = {
                    series: [{
                        name: 'Total Revenue',
                        data: @json($daily->pluck('total'))
                    }, {
                        name: 'Paid Amount',
                        data: @json($daily->pluck('paid_amount'))
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        fontFamily: 'inherit',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: @json($daily->pluck('date')),
                        type: 'category',
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return "৳" + value.toFixed(0);
                            }
                        }
                    },
                    colors: ['#0d6efd', '#198754'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.3,
                            stops: [0, 90, 100]
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "৳" + val.toFixed(2)
                            }
                        }
                    }
                };
                new ApexCharts(document.querySelector("#revenueTrendChart"), trendOptions).render();

                // Revenue Source Chart
                var sourceOptions = {
                    series: @json($byType->values()),
                    labels: @json($byType->keys()),
                    chart: {
                        type: 'donut',
                        height: 300,
                        fontFamily: 'inherit',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true
                                    },
                                    value: {
                                        formatter: function(val) {
                                            return "৳" + parseFloat(val).toFixed(2);
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function(w) {
                                            return "৳" + w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0).toFixed(2)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        position: 'bottom'
                    },
                    colors: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14'],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "৳" + val.toFixed(2)
                            }
                        }
                    }
                };
                new ApexCharts(document.querySelector("#revenueSourceChart"), sourceOptions).render();

                // Payment Methods Chart
                var paymentOptions = {
                    series: @json($paymentMethods->values()),
                    labels: @json($paymentMethods->keys()),
                    chart: {
                        type: 'pie',
                        height: 300,
                        fontFamily: 'inherit',
                        toolbar: {
                            show: true
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    colors: ['#198754', '#0dcaf0', '#ffc107', '#0d6efd'],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "৳" + val.toFixed(2)
                            }
                        }
                    }
                };
                new ApexCharts(document.querySelector("#paymentMethodsChart"), paymentOptions).render();
            });
        </script>
    @endpush
</x-app-layout>
