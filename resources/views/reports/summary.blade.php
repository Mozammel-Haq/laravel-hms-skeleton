<x-app-layout>
    @push('styles')
        <style>
            /* CSS Variables for theming */
            :root {
                /* Light mode colors */
                --kpi-bg-primary: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
                --kpi-bg-info: linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%);
                --kpi-bg-success: linear-gradient(135deg, #f0f9f5 0%, #e6f4ec 100%);
                --kpi-bg-warning: linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%);

                --kpi-border-primary: #c2d9ff;
                --kpi-border-info: #b6e7f2;
                --kpi-border-success: #b8e6cf;
                --kpi-border-warning: #ffe5b6;

                --primary-color: #0d6efd;
                --info-color: #17a2b8;
                --success-color: #198754;
                --warning-color: #ffc107;
            }

            [data-bs-theme="dark"] {
                /* Dark mode colors */
                --kpi-bg-primary: linear-gradient(135deg, #0d2b5c 0%, #0a1f42 100%);
                --kpi-bg-info: linear-gradient(135deg, #0a3d4c 0%, #072a35 100%);
                --kpi-bg-success: linear-gradient(135deg, #0d4229 0%, #0a2e1d 100%);
                --kpi-bg-warning: linear-gradient(135deg, #664d03 0%, #4d3a02 100%);

                --kpi-border-primary: #1e4b9e;
                --kpi-border-info: #166d84;
                --kpi-border-success: #157347;
                --kpi-border-warning: #996c00;

                --primary-color: #6ea8fe;
                --info-color: #6edff6;
                --success-color: #75b798;
                --warning-color: #ffda6a;
            }

            /* KPI Card Styles */
            .kpi-card {
                border: 1.5px solid;
                transition: all 0.3s ease;
            }

            /* Card specific backgrounds */
            .kpi-card:nth-child(1) {
                background: var(--kpi-bg-primary);
                border-color: var(--kpi-border-primary) !important;
            }

            .kpi-card:nth-child(2) {
                background: var(--kpi-bg-info);
                border-color: var(--kpi-border-info) !important;
            }

            .kpi-card:nth-child(3) {
                background: var(--kpi-bg-success);
                border-color: var(--kpi-border-success) !important;
            }

            .kpi-card:nth-child(4) {
                background: var(--kpi-bg-warning);
                border-color: var(--kpi-border-warning) !important;
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

            /* Icon Container */
            .kpi-icon-container {
                background: rgba(var(--bs-primary-rgb), 0.1) !important;
                border: 1px solid rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(2) .kpi-icon-container {
                background: rgba(var(--bs-info-rgb), 0.1) !important;
                border: 1px solid rgba(var(--bs-info-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(3) .kpi-icon-container {
                background: rgba(var(--bs-success-rgb), 0.1) !important;
                border: 1px solid rgba(var(--bs-success-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(4) .kpi-icon-container {
                background: rgba(var(--bs-warning-rgb), 0.1) !important;
                border: 1px solid rgba(var(--bs-warning-rgb), 0.2) !important;
            }

            /* Small Icon */
            .kpi-small-icon {
                border-color: rgba(var(--bs-primary-rgb), 0.3) !important;
                background-color: var(--bs-body-bg) !important;
            }

            .kpi-card:nth-child(2) .kpi-small-icon {
                border-color: rgba(var(--bs-info-rgb), 0.3) !important;
            }

            .kpi-card:nth-child(3) .kpi-small-icon {
                border-color: rgba(var(--bs-success-rgb), 0.3) !important;
            }

            .kpi-card:nth-child(4) .kpi-small-icon {
                border-color: rgba(var(--bs-warning-rgb), 0.3) !important;
            }

            /* Divider */
            .kpi-divider {
                border-color: rgba(var(--bs-primary-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(2) .kpi-divider {
                border-color: rgba(var(--bs-info-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(3) .kpi-divider {
                border-color: rgba(var(--bs-success-rgb), 0.2) !important;
            }

            .kpi-card:nth-child(4) .kpi-divider {
                border-color: rgba(var(--bs-warning-rgb), 0.2) !important;
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
                <h3 class="page-title">Executive Summary</h3>

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
                <form action="{{ route('reports.summary') }}" method="GET" class="row g-3 align-items-end">
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

        <!-- KPIs -->
        <div class="row mb-5 g-4">
            <!-- New Patients Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-1)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">NEW
                                    PATIENTS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $newPatientsTotal }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" />
                                    <path d="M5 20C5 16.134 8.13401 13 12 13C15.866 13 19 16.134 19 20"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21"
                                            stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path
                                            d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z"
                                            stroke="var(--primary-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Total: <span
                                        class="fw-semibold text-body">{{ $patientsTotal }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admissions Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-dots-2" x="0" y="0" width="30" height="30"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--info-color)"
                                        fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-dots-2)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                        style="background: radial-gradient(circle at bottom left, var(--info-color) 0%, transparent 70%); opacity: 0.1;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    ADMISSIONS</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $admissionsTotal }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21M19 21L21 21M19 21H14M5 21L3 21M5 21H10M10 14H14M10 18H14"
                                        stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M9 7H15" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 11H12" stroke="var(--info-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V12L15 15" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <circle cx="12" cy="12" r="9" stroke="var(--info-color)"
                                            stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">In selected period</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-lines-3" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <line x1="0" y1="40" x2="40" y2="0"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                    <line x1="0" y1="0" x2="40" y2="40"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-lines-3)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                        style="background: radial-gradient(circle at center, var(--success-color) 0%, transparent 70%); opacity: 0.08;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    REVENUE</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($invoicesTotal, 0) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
                                        stroke="var(--success-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 6V12L15 15" stroke="var(--success-color)" stroke-width="1.5"
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
                                            stroke="var(--success-color)" stroke-width="1.5" />
                                        <path d="M8 12H16" stroke="var(--success-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Invoiced Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Received Card -->
            <div class="col-md-3">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-diagonal-4" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <path d="M0 20L20 0" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M-10 10L10 -10" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                    <path d="M10 30L30 10" stroke="var(--warning-color)" stroke-width="0.5"
                                        stroke-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-diagonal-4)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-0 start-0 w-25 h-25 decorative-shape">
                        <svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0,0 L100,0 L0,100 Z" fill="var(--warning-color)" fill-opacity="0.1" />
                        </svg>
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                                    PAYMENTS RECEIVED</h6>
                                <h2 class="fw-bold kpi-value mb-0">৳{{ number_format($paymentsTotal, 0) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 12H22M6 16H10M16 16H18M5 20H19C20.1046 20 21 19.1046 21 18V6C21 4.89543 20.1046 4 19 4H5C3.89543 4 3 4.89543 3 6V18C3 19.1046 3.89543 20 5 20Z"
                                        stroke="var(--warning-color)" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M15 8H17" stroke="var(--warning-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2V6" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 18V22" stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path
                                            d="M17 20.66C16.09 21.15 15.07 21.44 14 21.44C9.58 21.44 6 17.86 6 13.44C6 9.02 9.58 5.44 14 5.44C15.07 5.44 16.09 5.73 17 6.22"
                                            stroke="var(--warning-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Collected Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Income Trend -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Income Trend</h5>
                    </div>
                    <div class="card-body">
                        <div id="incomeTrendChart"></div>
                    </div>
                </div>
            </div>
            <!-- Appointment Status -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Appointment Status</h5>
                    </div>
                    <div class="card-body">
                        <div id="appointmentStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0">Recent Admissions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admissions as $admission)
                                <tr>
                                    <td>
                                        <div class="fw-bold">
                                            {{ optional($admission->patient)->full_name ?? 'Unknown' }}</div>
                                        <p class="text-muted">{{ optional($admission->patient)->uhid }}</p>
                                    </td>
                                    <td>{{ optional($admission->doctor)->user?->name ?? 'Unknown' }}</td>
                                    <td>{{ $admission->created_at }}</td>
                                    <td>
                                        @if ($admission->status == 'discharged')
                                            <span class="badge bg-success">Discharged</span>
                                        @elseif($admission->status == 'admitted')
                                            <span class="badge bg-warning text-dark">Admitted</span>
                                        @else
                                            <span class="badge bg-primary">{{ ucfirst($admission->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No recent admissions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Income Trend Chart
            var incomeOptions = {
                series: [{
                    name: 'Revenue',
                    data: @json($incomeTrend->pluck('total'))
                }],
                chart: {
                    type: 'area',
                    height: 350,
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
                    },
                    fontFamily: 'inherit',
                    background: 'transparent'
                },
                theme: {
                    mode: document.documentElement.getAttribute('data-bs-theme') || 'light'
                },
                grid: {
                    borderColor: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#333' : '#e0e0e0',
                    strokeDashArray: 4,
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($incomeTrend->pluck('date')),
                    type: 'category',
                    labels: {
                        style: {
                            colors: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#adb5bd' :
                                '#6c757d',
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#adb5bd' :
                                '#6c757d',
                        },
                        formatter: function(value) {
                            return "৳" + value.toFixed(2);
                        }
                    }
                },
                colors: ['#198754'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light',
                    y: {
                        formatter: function(val) {
                            return "৳ " + val.toFixed(2)
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#incomeTrendChart"), incomeOptions).render();

            // Appointment Status Chart
            var statusOptions = {
                series: @json($appointmentStats->values()),
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'inherit',
                    background: 'transparent',
                    toolbar: {
                        show: true
                    }
                },
                theme: {
                    mode: document.documentElement.getAttribute('data-bs-theme') || 'light'
                },
                labels: @json($appointmentStats->keys()->map(fn($k) => ucfirst($k))),
                colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#adb5bd' : '#6c757d',
                    }
                },
                dataLabels: {
                    style: {
                        fontSize: '14px',
                        fontFamily: 'inherit',
                        fontWeight: 400,
                    }
                },
                tooltip: {
                    theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light',
                }
            };
            new ApexCharts(document.querySelector("#appointmentStatusChart"), statusOptions).render();
        </script>
    @endpush
</x-app-layout>
