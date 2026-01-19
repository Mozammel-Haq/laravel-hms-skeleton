<x-app-layout>
    @push('styles')
    @endpush
    <div class="container-fluid mt-3">
        <!-- Header & Controls -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="page-title">Patient Demographics</h3>
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
                <form action="{{ route('reports.demographics') }}" method="GET" class="row g-3 align-items-end">
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
                    <p class="mb-0">
                        Showing data from <span
                            class="fw-semibold text-primary">{{ $startDate->format('M d, Y') }}</span>
                        to <span class="fw-semibold text-primary">{{ $endDate->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row mb-5 g-4">
            <!-- New Patients Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-1a" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-1a)" />
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
                                <h2 class="fw-bold kpi-value mb-0">{{ $newPatients->sum('total') }}</h2>
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
                                        <path d="M12 8V12L15 15" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <circle cx="12" cy="12" r="9" stroke="var(--primary-color)"
                                            stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Registered in this period</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dominant Gender Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-dots-2a" x="0" y="0" width="30" height="30"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--info-color)"
                                        fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-dots-2a)" />
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
                                    DOMINANT GENDER</h6>
                                @php
                                    $topGender = $genderStats->sortDesc()->keys()->first() ?? 'N/A';
                                    $topGenderCount = $genderStats->sortDesc()->first() ?? 0;
                                    $totalGender = $genderStats->sum();
                                    $percentage =
                                        $totalGender > 0 ? round(($topGenderCount / $totalGender) * 100, 1) : 0;
                                @endphp
                                <h2 class="fw-bold kpi-value mb-0">{{ ucfirst($topGender) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                @if ($topGender == 'male')
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10 15C13.3137 15 16 12.3137 16 9C16 5.68629 13.3137 3 10 3C6.68629 3 4 5.68629 4 9C4 12.3137 6.68629 15 10 15Z"
                                            stroke="var(--info-color)" stroke-width="1.5" />
                                        <path
                                            d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                            stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                @elseif($topGender == 'female')
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15Z"
                                            stroke="var(--info-color)" stroke-width="1.5" />
                                        <path
                                            d="M6 21V19C6 17.9391 6.42143 16.9217 7.17157 16.1716C7.92172 15.4214 8.93913 15 10 15H14C15.0609 15 16.0783 15.4214 16.8284 16.1716C17.5786 16.9217 18 17.9391 18 19V21"
                                            stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M12 9V15" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M9 12H15" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                @else
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15Z"
                                            stroke="var(--info-color)" stroke-width="1.5" />
                                        <path
                                            d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                            stroke="var(--info-color)" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M12 9V12" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M10.5 10.5L13.5 13.5" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                            stroke="var(--info-color)" stroke-width="1.5" />
                                        <path d="M12 6V12L16 14" stroke="var(--info-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">{{ $percentage }}% of new patients</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Primary Age Group Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" data-bs-theme="light,dark">
                    <!-- Pattern Background -->
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-lines-3a" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <line x1="0" y1="40" x2="40" y2="0"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                    <line x1="0" y1="0" x2="40" y2="40"
                                        stroke="var(--success-color)" stroke-width="1" stroke-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-lines-3a)" />
                        </svg>
                    </div>

                    <!-- Decorative Shape -->
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                        style="background: radial-gradient(circle at center, var(--success-color) 0%, transparent 70%); opacity: 0.08;">
                    </div>

                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">PRIMARY
                                    AGE GROUP</h6>
                                @php
                                    $topAgeGroup = array_search(max($ageGroups), $ageGroups);
                                @endphp
                                <h2 class="fw-bold kpi-value mb-0">{{ $topAgeGroup }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                        stroke="var(--success-color)" stroke-width="1.5" />
                                    <path d="M12 6V12L16 14" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M7.5 12H16.5" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M12 7.5V16.5" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V12L15 15" stroke="var(--success-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 2V6" stroke="var(--success-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M12 18V22" stroke="var(--success-color)" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Years Old</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <!-- Registration Trend -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Patient Registration Trend</h5>
                    </div>
                    <div class="card-body">
                        <div id="registrationTrendChart"></div>
                    </div>
                </div>
            </div>
            <!-- Gender Distribution -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Gender Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="genderPieChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <!-- Age Groups -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Age Group Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="ageGroupChart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Registration Trend Chart
            var trendOptions = {
                series: [{
                    name: 'New Patients',
                    data: @json($newPatients->pluck('total'))
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
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: @json($newPatients->pluck('date')),
                    type: 'category',
                    labels: {
                        rotate: -45,
                        rotateAlways: false
                    }
                },
                yaxis: {
                    title: {
                        text: 'Count'
                    }
                },
                colors: ['#0d6efd'],
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
            new ApexCharts(document.querySelector("#registrationTrendChart"), trendOptions).render();

            // Gender Pie Chart
            var genderOptions = {
                series: @json($genderStats->values()),
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: @json($genderStats->keys()->map(fn($k) => ucfirst($k))),
                colors: ['#0d6efd', '#d63384', '#ffc107'],
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
                }
            };
            new ApexCharts(document.querySelector("#genderPieChart"), genderOptions).render();

            // Age Group Bar Chart
            var ageOptions = {
                series: [{
                    name: 'Patients',
                    data: @json(array_values($ageGroups))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '55%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json(array_keys($ageGroups)),
                    title: {
                        text: 'Age Range'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Count'
                    }
                },
                colors: ['#198754']
            };
            new ApexCharts(document.querySelector("#ageGroupChart"), ageOptions).render();
        </script>
    @endpush
</x-app-layout>
