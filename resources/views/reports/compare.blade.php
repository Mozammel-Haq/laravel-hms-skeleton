<x-app-layout>
    <div class="container-fluid mt-3">
        <!-- Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="page-title">Clinic Comparison</h3>

            </div>
            <div class="col-md-6 text-end d-print-none">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" class="btn btn-success me-2">
                    <i class="bi bi-file-spreadsheet"></i> Export Excel
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Print / PDF
                </button>
            </div>
        </div>

        <!-- Selection Form -->
        <div class="card mb-4 d-print-none">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Select Clinics to Compare</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.compare') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="form-label">Clinics</label>
                            <div class="row">
                                @foreach ($clinics as $clinic)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="clinics[]"
                                                value="{{ $clinic->id }}" id="clinic_{{ $clinic->id }}"
                                                {{ in_array($clinic->id, $clinicIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="clinic_{{ $clinic->id }}">
                                                {{ $clinic->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary w-100">Compare</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($selectedClinics->isNotEmpty())
            @php
                $clinicNames = $selectedClinics->pluck('name');
                $revenueData = $selectedClinics->map(fn($c) => $stats[$c->id]['revenue']);
                $patientData = $selectedClinics->map(fn($c) => $stats[$c->id]['patients']);
                $appointmentData = $selectedClinics->map(fn($c) => $stats[$c->id]['appointments']);
                $staffData = $selectedClinics->map(fn($c) => $stats[$c->id]['staff']);
            @endphp

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Revenue Comparison -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title mb-0">Revenue Comparison</h5>
                        </div>
                        <div class="card-body">
                            <div id="revenueChart"></div>
                        </div>
                    </div>
                </div>
                <!-- Volume Comparison -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title mb-0">Operational Volume</h5>
                        </div>
                        <div class="card-body">
                            <div id="volumeChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Detailed Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-hover table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Metric</th>
                                    @foreach ($selectedClinics as $clinic)
                                        <th>{{ $clinic->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold">Revenue</td>
                                    @foreach ($selectedClinics as $clinic)
                                        <td class="text-success fw-bold">
                                            ৳{{ number_format($stats[$clinic->id]['revenue'], 2) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold">New Patients</td>
                                    @foreach ($selectedClinics as $clinic)
                                        <td>{{ number_format($stats[$clinic->id]['patients']) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold">Appointments</td>
                                    @foreach ($selectedClinics as $clinic)
                                        <td>{{ number_format($stats[$clinic->id]['appointments']) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold">Staff Count (Total)</td>
                                    @foreach ($selectedClinics as $clinic)
                                        <td>{{ number_format($stats[$clinic->id]['staff']) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
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
                            data: @json($revenueData)
                        }],
                        chart: {
                            type: 'bar',
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
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                distributed: true,
                                horizontal: false,
                                columnWidth: '55%',
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        grid: {
                            borderColor: '#f1f1f1',
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 10
                            }
                        },
                        xaxis: {
                            categories: @json($clinicNames),
                            labels: {
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return "৳" + val.toFixed(2);
                                }
                            }
                        },
                        tooltip: {
                            theme: 'light',
                            y: {
                                formatter: function(val) {
                                    return "৳" + val.toFixed(2);
                                }
                            }
                        },
                        fill: {
                            opacity: 0.9
                        }
                    };
                    new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();

                    // Volume Chart
                    var volumeOptions = {
                        series: [{
                            name: 'Patients',
                            data: @json($patientData)
                        }, {
                            name: 'Appointments',
                            data: @json($appointmentData)
                        }],
                        chart: {
                            type: 'bar',
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
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 4
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        grid: {
                            borderColor: '#f1f1f1',
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 10
                            }
                        },
                        xaxis: {
                            categories: @json($clinicNames),
                        },
                        yaxis: {
                            title: {
                                text: 'Count'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        colors: ['#0d6efd', '#ffc107'],
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val
                                }
                            }
                        }
                    };
                    new ApexCharts(document.querySelector("#volumeChart"), volumeOptions).render();
                </script>
            @endpush
        @else
            <div class="alert alert-info">
                Please select at least one clinic to view comparison reports.
            </div>
        @endif
    </div>
</x-app-layout>
