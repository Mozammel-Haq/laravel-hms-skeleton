<x-app-layout>
    {{-- <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Super Admin Dashboard</h4>
            <p>Cross-clinic governance and analytics</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Clinics</h5>
                    <h3>{{ \App\Models\Clinic::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Global Reports</h5>
                    <p>View system-wide analytics</p>
                    <a href="#" class="btn btn-primary btn-sm">View Reports</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>System Health</h5>
                    <span class="badge bg-success">Operational</span>
                </div>
            </div>
        </div>
    </div> --}}
<div class="content pb-0">

    <!-- Page Header -->
    <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0">System Dashboard</h4>
            <p class="fs-13 mb-0 text-muted">Cross-clinic overview and system analytics</p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <a href="create-clinic.html" class="btn btn-primary d-inline-flex align-items-center">
                <i class="ti ti-plus me-1"></i>Create Clinic
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- KPI Row -->
<div class="row">

    <!-- Total Clinics -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="./assets/img/bg/bg-01.svg" class="position-absolute start-0 top-0" alt="">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span class="avatar bg-primary rounded-circle">
                        <i class="ti ti-building-hospital fs-24"></i>
                    </span>
                    <div class="text-end">
                        <span class="badge bg-success fs-12">+12%</span>
                        <p class="fs-13 mb-0">Last 30 Days</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1">Total Clinics</p>
                        <h3 class="fw-bold mb-0">48</h3>
                    </div>
                    <div id="chart-clinics" style="width:90px;height:54px;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- Clinic Admin Accounts -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="./assets/img/bg/bg-02.svg" class="position-absolute start-0 top-0" alt="">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span class="avatar bg-info rounded-circle">
                        <i class="ti ti-users fs-24"></i>
                    </span>
                    <div class="text-end">
                        <span class="badge bg-success fs-12">+8%</span>
                        <p class="fs-13 mb-0">Last 30 Days</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1">Clinic Admin</p>
                        <h3 class="fw-bold mb-0">48</h3>
                    </div>
                    <div id="chart-admins" style="width:90px;height:54px;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- System Users -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="./assets/img/bg/bg-03.svg" class="position-absolute start-0 top-0" alt="">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span class="avatar bg-warning rounded-circle">
                        <i class="ti ti-user-circle fs-24"></i>
                    </span>
                    <div class="text-end">
                        <span class="badge bg-success fs-12">+5%</span>
                        <p class="fs-13 mb-0">Last 30 Days</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1">System Users</p>
                        <h3 class="fw-bold mb-0">6,320</h3>
                    </div>
                    <div id="chart-users" style="width:90px;height:54px;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="./assets/img/bg/bg-04.svg" class="position-absolute start-0 top-0" alt="">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span class="avatar bg-success rounded-circle">
                        <i class="ti ti-currency-dollar fs-24"></i>
                    </span>
                    <div class="text-end">
                        <span class="badge bg-success fs-12">+18%</span>
                        <p class="fs-13 mb-0">Last 30 Days</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1">Revenue</p>
                        <h3 class="fw-bold mb-0">$1,245,800</h3>
                    </div>
                    <div id="chart-revenue" style="width:90px;height:54px;"></div>
                </div>

            </div>
        </div>
    </div>

</div>


    <!-- Charts Row -->
    <div class="row">

<div class="col-xl-8 card">
    <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">System Activity Statistics</h5>

    <div class="dropdown">
        <a href="javascript:void(0);"
           class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
           data-bs-toggle="dropdown"
           aria-expanded="false"
           id="systemChartRange">
            Monthly <i class="ti ti-chevron-down ms-1"></i>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" data-range="monthly">Monthly</a></li>
            <li><a class="dropdown-item" href="#" data-range="weekly">Weekly</a></li>
            <li><a class="dropdown-item" href="#" data-range="yearly">Yearly</a></li>
        </ul>
    </div>
    </div>
    <div class="card-body">
            <div id="system-activity-chart" style="min-height:280px;">
                <!-- Jan–May: Clinics created, users onboarded -->
            </div>
        </div>

</div>


        <div class="col-xl-4 col-lg-6 d-flex">
    <div class="card shadow-sm flex-fill w-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">Revenue by Clinics</h5>
            <div class="dropdown">
                <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                    Monthly <i class="ti ti-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Monthly</a></li>
                    <li><a class="dropdown-item" href="#">Weekly</a></li>
                    <li><a class="dropdown-item" href="#">Yearly</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="fw-semibold mb-1 text-dark">City Care Clinic</p>
                    <p class="mb-0">Dhaka</p>
                </div>
                <h6 class="fw-bold mb-0">$245,000</h6>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="fw-semibold mb-1 text-dark">HealthPlus Center</p>
                    <p class="mb-0">Chittagong</p>
                </div>
                <h6 class="fw-bold mb-0">$198,400</h6>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="fw-semibold mb-1 text-dark">MedLife Clinic</p>
                    <p class="mb-0">Sylhet</p>
                </div>
                <h6 class="fw-bold mb-0">$172,900</h6>
            </div>
        </div>
    </div>
</div>


    </div>

    <!-- Clinic List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">Clinic List</h5>
                    <a href="clinics.html" class="btn btn-outline-white">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light">
                                <tr>
                                    <th>Clinic</th>
                                    <th>Admin</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <h6 class="mb-0 fw-semibold">City Care Clinic</h6>
                                    </td>
                                    <td>admin.citycare@example.com</td>
                                    <td>Dhaka</td>
                                    <td><span class="badge badge-soft-success border border-success">Active</span></td>
                                </tr>

                                <tr>
                                    <td>
                                        <h6 class="mb-0 fw-semibold">HealthPlus Center</h6>
                                    </td>
                                    <td>admin.healthplus@example.com</td>
                                    <td>Chittagong</td>
                                    <td><span class="badge badge-soft-success border border-success">Active</span></td>
                                </tr>

                                <tr>
                                    <td>
                                        <h6 class="mb-0 fw-semibold">MedLife Clinic</h6>
                                    </td>
                                    <td>admin.medlife@example.com</td>
                                    <td>Sylhet</td>
                                    <td><span class="badge badge-soft-danger border border-danger">Inactive</span></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cross-Clinic Patient Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Recent Cross-Clinic Patient Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light">
                                <tr>
                                    <th>Clinic</th>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>City Care Clinic</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar me-2">
                                                <img src="assets/img/profiles/avatar-02.jpg" class="rounded-circle" alt="">
                                            </span>
                                            <div>
                                                <h6 class="fs-14 mb-0">Ahmed Rahman</h6>
                                                <small>+880 1712 456789</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>02 Jun 2025</td>
                                    <td>In-Person</td>
                                    <td><span class="badge badge-soft-success border border-success">Completed</span></td>
                                </tr>

                                <tr>
                                    <td>HealthPlus Center</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar me-2">
                                                <img src="assets/img/profiles/avatar-06.jpg" class="rounded-circle" alt="">
                                            </span>
                                            <div>
                                                <h6 class="fs-14 mb-0">Sadia Akter</h6>
                                                <small>+880 1819 332211</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>02 Jun 2025</td>
                                    <td>Online</td>
                                    <td><span class="badge badge-soft-info border border-info">Scheduled</span></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</x-app-layout>
