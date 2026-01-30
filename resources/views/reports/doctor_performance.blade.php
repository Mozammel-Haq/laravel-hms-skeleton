<x-app-layout>
    <div class="container-fluid">
        <!-- Header & Controls -->
        <div class="row mb-4 align-items-center mt-3">
            <div class="col-md-6">
                <h3 class="page-title">Doctor Performance</h3>
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
        <div class="collapse mb-2 {{ request('range') == 'custom' ? 'show' : '' }}" id="customDateRange">
            <div class="card card-body">
                <form action="{{ route('reports.doctor_performance') }}" method="GET"
                    class="row g-3 align-items-end">
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
            // Prepare Chart Data
            $top5 = $topDoctors->take(5);
            $names = $top5->pluck('name');
            $revenues = $top5->pluck('revenue');
            $consults = $top5->pluck('consults');
            $admissions = $top5->pluck('admissions');
        @endphp

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Revenue Leaders -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Top 5 Revenue Leaders</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
            <!-- Workload -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">Workload Distribution (Top 5)</h5>
                    </div>
                    <div class="card-body">
                        <div id="workloadChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0">Detailed Performance Metrics</h5>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Department</th>
                                <th class="text-center">Consultations</th>
                                <th class="text-center">Admissions</th>
                                <th class="text-end">Revenue Generated</th>
                                <th class="text-center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topDoctors as $row)
                                <tr>
                                    <td>
                                        <a href="{{ route('doctors.show', $row['id']) }}"
                                            class="text-decoration-none fw-bold">
                                            {{ $row['name'] }}
                                        </a>
                                    </td>
                                    <td>{{ $row['department'] }}</td>
                                    <td class="text-center">{{ $row['consults'] }}</td>
                                    <td class="text-center">{{ $row['admissions'] }}</td>
                                    <td class="text-end">৳{{ number_format($row['revenue'], 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $row['score'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Revenue Chart
            var revenueOptions = {
                series: [{
                    name: 'Revenue',
                    data: @json($revenues)
                }],
                chart: {
                    type: 'bar',
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
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($names),
                    labels: {
                        formatter: function(val) {
                            return "৳" + val;
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                colors: ['#198754'],
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
            new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();

            // Workload Chart
            var workloadOptions = {
                series: [{
                    name: 'Consultations',
                    data: @json($consults)
                }, {
                    name: 'Admissions',
                    data: @json($admissions)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
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
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 4
                    },
                },
                xaxis: {
                    categories: @json($names),
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                legend: {
                    position: 'top',
                },
                fill: {
                    opacity: 1
                },
                colors: ['#0d6efd', '#ffc107'],
                grid: {
                    borderColor: '#f1f1f1',
                }
            };
            new ApexCharts(document.querySelector("#workloadChart"), workloadOptions).render();
        </script>
    @endpush
</x-app-layout>
