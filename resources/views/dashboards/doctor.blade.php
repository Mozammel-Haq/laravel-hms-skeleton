<x-app-layout>


    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-0">Doctor Dashboard</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <a href="javascript:void(0);" class="btn btn-primary d-inline-flex align-items-center"
                    data-bs-toggle="offcanvas" data-bs-target="#new_appointment"><i class="ti ti-plus me-1"></i>New
                    Appointment</a>
                <a href="javascript:void(0);" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i
                        class="ti ti-calendar-time me-1"></i>Schedule Availability</a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- row start -->
        <div class="row">

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Total Appointments</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">658</h3>
                                    <span class="badge fw-medium bg-success flex-shrink-0">+95%</span>
                                </div>
                            </div>
                            <span class="avatar border border-primary text-primary rounded-2 flex-shrink-0"><i
                                    class="ti ti-calendar-heart fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-5" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-success flex-shrink-0 ms-2">+21% <i
                                    class="ti ti-arrow-up ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Online Consultations</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">125</h3>
                                    <span class="badge fw-medium bg-danger flex-shrink-0">-15%</span>
                                </div>
                            </div>
                            <span class="avatar border border-danger text-danger rounded-2 flex-shrink-0"><i
                                    class="ti ti-users fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-6" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-danger flex-shrink-0 ms-2">+21% <i
                                    class="ti ti-arrow-down ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Cancelled Appointments</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">35</h3>
                                    <span class="badge fw-medium bg-success flex-shrink-0">+45%</span>
                                </div>
                            </div>
                            <span class="avatar border border-success text-success rounded-2 flex-shrink-0"><i
                                    class="ti ti-versions fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-7" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-success flex-shrink-0 ms-2">+31% <i
                                    class="ti ti-arrow-up ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

        </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row">

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <!-- card start -->
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-truncate">Upcoming Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Today <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Today</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">This Week</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">This Month</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                <img src="{{ asset('assets') }}/img/doctors/doctor-01.jpg" alt="img"
                                    class="rounded-circle">
                            </a>
                            <div>
                                <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                        class="fw-semibold">Andrew Billard</a></h6>
                                <p class="mb-0 fs-13 text-truncate">#AP455698</p>
                            </div>
                        </div>
                        <h6 class="fs-14 fw-semibold mb-1">General Visit</h6>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <p class="mb-0 d-inline-flex align-items-center"><i
                                    class="ti ti-calendar-time text-dark me-1"></i>Monday, 31 Mar 2025</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i
                                    class="ti ti-clock text-dark me-1"></i>06:30 PM </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h6 class="fs-13 fw-semibold mb-1">Department</h6>
                                <p>Cardiology</p>
                            </div>
                            <div class="col">
                                <h6 class="fs-13 fw-semibold mb-1">Type</h6>
                                <p class="text-truncate">Online Consultation</p>
                            </div>
                        </div>
                        <div class="my-3 border-bottom pb-3">
                            <a href="javascript:void(0);" class="btn btn-primary w-100">Start Appointment</a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="javascript:void(0);" class="btn btn-dark w-100"><i
                                    class="ti ti-brand-hipchat me-1"></i>Chat Now</a>
                            <a href="javascript:void(0);" class="btn btn-outline-white w-100"><i
                                    class="ti ti-video me-1"></i>Video Consutation</a>
                        </div>
                    </div>
                </div>
                <!-- card end -->
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-8 d-flex">
                <!-- card start -->
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Monthly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 flex-wrap mb-3">
                            <p class="mb-0 d-inline-flex align-items-center"><i
                                    class="ti ti-point-filled me-1 fs-18 text-primary"></i>Total Appointments</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i
                                    class="ti ti-point-filled me-1 fs-18 text-success"></i>Completed Appointments</p>
                        </div>
                        <div class="chart-set" id="s-col-20"></div>
                    </div>
                </div>
                <!-- card end -->
            </div>
            <!-- col end -->
        </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row row-cols-1 row-cols-xl-6 row-cols-md-3 row-cols-sm-2">

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-primary rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-user"></i></span>
                        <p class="mb-1 text-truncate">Total Patient</p>
                        <h3 class="fw-bold mb-2">658</h3>
                        <p class="mb-0 text-success text-truncate">+31% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-secondary rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-video"></i></span>
                        <p class="mb-1 text-truncate">Video Consultation</p>
                        <h3 class="fw-bold mb-2">256</h3>
                        <p class="mb-0 text-danger text-truncate">-21% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-success rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-calendar-up"></i></span>
                        <p class="mb-1 text-truncate">Rescheduled</p>
                        <h3 class="fw-bold mb-2">141</h3>
                        <p class="mb-0 text-success text-truncate">+64% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-danger rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-checklist"></i></span>
                        <p class="mb-1 text-truncate">Pre Visit Bookings</p>
                        <h3 class="fw-bold mb-2">524</h3>
                        <p class="mb-0 text-success text-truncate">+38% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-info rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-calendar-share"></i></span>
                        <p class="mb-1 text-truncate">Walkin Bookings</p>
                        <h3 class="fw-bold mb-2">21</h3>
                        <p class="mb-0 text-success text-truncate">+95% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-soft-success text-success rounded-2 fs-20 d-inline-flex mb-2"><i
                                class="ti ti-carousel-vertical"></i></span>
                        <p class="mb-1 text-truncate">Follow Ups</p>
                        <h3 class="fw-bold mb-2">451</h3>
                        <p class="mb-0 text-success text-truncate">+76% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

        </div>
        <!-- row start -->

        <!-- row start -->
        <div class="row">
            <div class="col-12 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Recent Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Weekly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Table start -->
                        <div class="table-responsive table-nowrap">
                            <table class="table border">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date & Time</th>
                                        <th>Mode</th>
                                        <th>Status</th>
                                        <th>Consultation Fees</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar me-2">
                                                    <img src="{{ asset('assets') }}/img/profiles/avatar-06.jpg"
                                                        alt="img" class="rounded-circle">
                                                </a>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><a href="javascript:void(0);"
                                                            class="fw-medium">Alberto Ripley</a></h6>
                                                    <p class="mb-0 fs-13">+1 56556 54565</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>27 May 2025 - 09:30 AM</td>
                                        <td>Online</td>
                                        <td><span class="badge bg-success fw-medium">Checked Out</span></td>
                                        <td class="fw-semibold text-dark">$400</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                <i class="ti ti-calendar-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#edit_appointment"><i
                                                            class="ti ti-edit me-2"></i>Edit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                                            class="ti ti-trash me-2"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar me-2">
                                                    <img src="{{ asset('assets') }}/img/profiles/avatar-12.jpg"
                                                        alt="img" class="rounded-circle">
                                                </a>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><a href="javascript:void(0);"
                                                            class="fw-medium">Susan Babin</a></h6>
                                                    <p class="mb-0 fs-13">+1 65658 95654</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>26 May 2025 - 10:15 AM</td>
                                        <td>Online</td>
                                        <td><span class="badge bg-warning fw-medium">Checked in</span></td>
                                        <td class="fw-semibold text-dark">$370</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                <i class="ti ti-calendar-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#edit_appointment"><i
                                                            class="ti ti-edit me-2"></i>Edit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                                            class="ti ti-trash me-2"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar me-2">
                                                    <img src="{{ asset('assets') }}/img/profiles/avatar-08.jpg"
                                                        alt="img" class="rounded-circle">
                                                </a>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><a href="javascript:void(0);"
                                                            class="fw-medium">Carol Lam</a></h6>
                                                    <p class="mb-0 fs-13">+1 55654 56647</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>25 May 2025 - 02:40 PM</td>
                                        <td>In-Person</td>
                                        <td><span class="badge bg-danger fw-medium">Cancelled</span></td>
                                        <td class="fw-semibold text-dark">$450</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                <i class="ti ti-calendar-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#edit_appointment"><i
                                                            class="ti ti-edit me-2"></i>Edit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                                            class="ti ti-trash me-2"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar me-2">
                                                    <img src="{{ asset('assets') }}/img/profiles/avatar-22.jpg"
                                                        alt="img" class="rounded-circle">
                                                </a>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><a href="javascript:void(0);"
                                                            class="fw-medium">Marsha Noland</a></h6>
                                                    <p class="mb-0 fs-13">+1 65668 54558</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>24 May 2025 - 11:30 AM</td>
                                        <td>In-Person</td>
                                        <td><span class="badge bg-info fw-medium">Schedule</span></td>
                                        <td class="fw-semibold text-dark">$310</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                <i class="ti ti-calendar-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#edit_appointment"><i
                                                            class="ti ti-edit me-2"></i>Edit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                                            class="ti ti-trash me-2"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="avatar me-2">
                                                    <img src="{{ asset('assets') }}/img/profiles/avatar-25.jpg"
                                                        alt="img" class="rounded-circle">
                                                </a>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><a href="javascript:void(0);"
                                                            class="fw-medium">John Elsass</a></h6>
                                                    <p class="mb-0 fs-13">47851263</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>23 May 2025 - 04:10 PM</td>
                                        <td>Online</td>
                                        <td><span class="badge bg-info fw-medium">Schedule</span></td>
                                        <td class="fw-semibold text-dark">$400</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                <i class="ti ti-calendar-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#edit_appointment"><i
                                                            class="ti ti-edit me-2"></i>Edit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                                            class="ti ti-trash me-2"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Table end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row">

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Availability</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Trustcare Clinic <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">CureWell Medical Hub</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Trustcare Clinic</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">NovaCare Medical</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Greeny Medical Clinic</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Mon</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Tue</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Wed</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Thu</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Fri</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Sat</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM
                                - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2">
                            <p class="text-dark fw-semibold mb-0">Sun</p>
                            <p class="mb-0 d-inline-flex align-items-center text-danger"><i
                                    class="ti ti-clock me-1"></i>Closed</p>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-light w-100 mt-2 fs-13">Edit Availability</a>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-truncate">Appointment Statistics</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Monthly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="circle-chart-2" class="chart-set"></div>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-3">
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i
                                        class="ti ti-circle-filled text-success fs-10 me-1"></i>Completed</p>
                                <h5 class="fw-bold mb-0">260</h5>
                            </div>
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i
                                        class="ti ti-circle-filled text-warning fs-10 me-1"></i>Pending</p>
                                <h5 class="fw-bold mb-0">21</h5>
                            </div>
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i
                                        class="ti ti-circle-filled text-danger fs-10 me-1"></i>Cancelled</p>
                                <h5 class="fw-bold mb-0">50</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Top Patients</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Weekly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/profiles/avatar-06.jpg" alt="img"
                                        class="rounded-circle">
                                </a>
                                <div>
                                    <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                            class="fw-medium">Alberto Ripley</a></h6>
                                    <p class="mb-0 fs-13 text-truncate">+1 56556 54565</p>
                                </div>
                            </div>
                            <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">20
                                Appointments</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/profiles/avatar-12.jpg" alt="img"
                                        class="rounded-circle">
                                </a>
                                <div>
                                    <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                            class="fw-medium">Susan Babin</a></h6>
                                    <p class="mb-0 fs-13 text-truncate">+1 65658 95654</p>
                                </div>
                            </div>
                            <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">18
                                Appointments</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/profiles/avatar-08.jpg" alt="img"
                                        class="rounded-circle">
                                </a>
                                <div>
                                    <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                            class="fw-medium">Carol Lam</a></h6>
                                    <p class="mb-0 fs-13 text-truncate">+1 55654 56647</p>
                                </div>
                            </div>
                            <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">16
                                Appointments</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/profiles/avatar-22.jpg" alt="img"
                                        class="rounded-circle">
                                </a>
                                <div>
                                    <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                            class="fw-medium">Marsha Noland</a></h6>
                                    <p class="mb-0 fs-13 text-truncate">+1 65668 54558</p>
                                </div>
                            </div>
                            <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">14
                                Appointments</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/profiles/avatar-17.jpg" alt="img"
                                        class="rounded-circle">
                                </a>
                                <div>
                                    <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);"
                                            class="fw-medium">Irma Armstrong</a></h6>
                                    <p class="mb-0 fs-13 text-truncate">+1 45214 66568</p>
                                </div>
                            </div>
                            <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">12
                                Appointments</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

        </div>
        <!-- row end -->

    </div>
</x-app-layout>
