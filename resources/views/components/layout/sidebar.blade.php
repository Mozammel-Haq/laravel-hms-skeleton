<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('dashboard') }}" class="logo logo-normal">
                <img src="{{ asset('assets') }}/img/logo.svg" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('dashboard') }}" class="logo-small">
                <img src="{{ asset('assets') }}/img/logo-small.svg" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('dashboard') }}" class="dark-logo">
                <img src="{{ asset('assets') }}/img/logo-white.svg" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-left text-body"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <!-- Clinic Info / Switcher -->
            <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3 dropend">
                <a href="javascript:void(0);" class="drop-arrow-none"
                    @if (auth()->check() &&
                            (auth()->user()->hasRole('Super Admin') ||
                                (auth()->user()->hasRole('Doctor') && isset($doctorClinics) && $doctorClinics->count() > 1))) data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false" @endif>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="avatar rounded-circle flex-shrink-0 p-2"><img
                                    src="{{ asset('assets') }}/img/icons/trustcare.svg" alt="img"></span>
                            <div class="ms-2">
                                <h6 class="fs-14 fw-semibold mb-0">
                                    {{ optional($currentClinic)->name ?? (auth()->user()->clinic->name ?? 'Trustcare Clinic') }}
                                </h6>
                                <p class="fs-13 mb-0">
                                    {{ optional($currentClinic)->location ?? (auth()->user()->clinic->location ?? 'Location') }}
                                </p>
                            </div>
                        </div>
                        @if (auth()->check() &&
                                (auth()->user()->hasRole('Super Admin') ||
                                    (auth()->user()->hasRole('Doctor') && isset($doctorClinics) && $doctorClinics->count() > 1)))
                            <i class="ti ti-arrows-transfer-up"></i>
                        @endif
                    </div>
                </a>

                @if (auth()->check() && auth()->user()->hasRole('Super Admin'))
                    <div class="dropdown-menu" style="min-width: 250px;">
                        <!-- Single Switch Section -->
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-2 text-uppercase fs-11 text-muted">Switch Active Clinic</h6>
                            <div style="max-height: 150px; overflow-y: auto;">
                                @foreach ($allClinics as $clinic)
                                    <a class="dropdown-item d-flex justify-content-between align-items-center px-2 py-1 rounded {{ $currentClinic->id == $clinic->id ? 'bg-light' : '' }}"
                                        href="{{ route('system.switch-clinic', $clinic->id) }}">
                                        <span class="text-truncate"
                                            style="max-width: 150px;">{{ $clinic->name }}</span>
                                        @if ($currentClinic->id == $clinic->id)
                                            <i class="ti ti-check text-success"></i>
                                        @else
                                            <i class="ti ti-chevron-right fs-12"></i>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Multi-Select Comparison Section -->
                        <div class="px-3 py-2 bg-light">
                            <h6 class="mb-2 text-uppercase fs-11 text-muted">Compare & Reports</h6>
                            <form action="{{ route('reports.compare') }}" method="GET">
                                <div class="mb-2" style="max-height: 150px; overflow-y: auto;">
                                    @foreach ($allClinics as $clinic)
                                        <div class="form-check form-check-sm mb-1">
                                            <input class="form-check-input" type="checkbox" name="clinics[]"
                                                value="{{ $clinic->id }}" id="compare_clinic_{{ $clinic->id }}">
                                            <label class="form-check-label fs-13 text-truncate d-block"
                                                for="compare_clinic_{{ $clinic->id }}" style="max-width: 180px;">
                                                {{ $clinic->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="ti ti-chart-bar me-1"></i> Generate Report
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if (auth()->check() && auth()->user()->hasRole('Doctor') && isset($doctorClinics) && $doctorClinics->count() > 1)
                    <div class="dropdown-menu" style="min-width: 250px;">
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-2 text-uppercase fs-11 text-muted">Switch Active Clinic</h6>
                            <div style="max-height: 150px; overflow-y: auto;">
                                @foreach ($doctorClinics as $clinic)
                                    <a class="dropdown-item d-flex justify-content-between align-items-center px-2 py-1 rounded {{ $currentClinic->id == $clinic->id ? 'bg-light' : '' }}"
                                        href="{{ route('doctor.switch-clinic', $clinic->id) }}">
                                        <span class="text-truncate"
                                            style="max-width: 150px;">{{ $clinic->name }}</span>
                                        @if ($currentClinic->id == $clinic->id)
                                            <i class="ti ti-check text-success"></i>
                                        @else
                                            <i class="ti ti-chevron-right fs-12"></i>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <ul>
                <li class="menu-title"><span>Main Menu</span></li>

                <!-- Dashboard (Common) -->
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                    </a>
                </li>

                <!-- 1. SUPER ADMIN FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Super Admin'))
                    <li class="menu-title"><span>System Governance</span></li>
                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('clinics.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-building-hospital"></i><span>Clinics</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('clinics.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('clinics.create') }}">Create Clinic</a></li>
                            <li><a href="{{ route('clinics.index') }}">Clinic List</a></li>
                            <li><a href="{{ route('reports.index') }}">Clinic Reports</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('users.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-users"></i><span>Users</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('users.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('admin.super-admin-users.index') }}">Super Admins</a></li>
                            <li><a href="{{ route('admin.clinic-admin-users.index') }}">Clinic Admin Accounts</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-settings"></i><span>System Settings</span><span class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('admin.roles.index') }}"
                                    class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles</a></li>
                            <li><a href="{{ route('admin.permissions.index') }}"
                                    class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">Permissions</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('reports.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-report"></i><span>Global Reports</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('reports.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('reports.financial') }}">Revenue (All Clinics)</a></li>
                            <li><a href="{{ route('reports.demographics') }}">Patient Volume</a></li>
                        </ul>
                    </li>
                @endif

                <!-- 2. CLINIC ADMIN FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Clinic Admin'))
                    <li class="menu-title"><span>Clinic Management</span></li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('departments.*') || request()->routeIs('doctors.*') || request()->routeIs('staff.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-settings"></i><span>Clinic Setup</span><span class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('departments.*') || request()->routeIs('doctors.*') || request()->routeIs('staff.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('clinics.profile') }}">Clinic Profile</a></li>
                            <li><a href="{{ route('departments.index') }}"
                                    class="{{ request()->routeIs('departments.*') ? 'active' : '' }}">Departments</a>
                            </li>
                            <li class="submenu">
                                <a href="#">Doctors <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="{{ route('doctors.index') }}">Doctor Profiles</a></li>
                                    <li><a href="{{ route('doctors.assignment') }}">Clinic Assignment</a></li>
                                    <li><a href="{{ route('doctors.schedules') }}">Schedules</a></li>
                                    <li><a href="{{ route('admin.schedule.exceptions.index') }}">Schedule
                                            Exceptions</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#">Users <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="{{ route('staff.create') }}">Create User</a></li>
                                    <li><a href="{{ route('staff.index') }}">Assign Roles</a></li>
                                    <li><a href="{{ route('staff.passwords') }}">Reset Passwords</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('appointments.*') || request()->routeIs('patients.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-user-check"></i><span>OPD</span><span class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('appointments.*') || request()->routeIs('patients.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('appointments.index') }}"
                                    class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">Appointments</a>
                            </li>
                            <li><a href="{{ route('patients.index') }}"
                                    class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">Patients</a></li>
                            <li><a href="{{ route('visits.index') }}">Visits</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('ipd.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-bed"></i><span>IPD</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('ipd.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('ipd.wards.index') }}">Wards</a></li>
                            <li><a href="{{ route('ipd.rooms.index') }}">Rooms</a></li>
                            <li><a href="{{ route('ipd.beds.index') }}">Beds</a></li>
                            <li><a href="{{ route('ipd.index') }}"
                                    class="{{ request()->routeIs('ipd.*') ? 'active' : '' }}">Admissions</a></li>
                            <li><a href="{{ route('ipd.bed_assignments.index') }}">Bed Assignments</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('clinical.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-stethoscope"></i><span>Clinical</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('clinical.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('clinical.consultations.index') }}">Consultations</a></li>
                            <li><a href="{{ route('clinical.prescriptions.index') }}">Prescriptions</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('lab.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-test-pipe"></i><span>Lab</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('lab.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('lab.catalog.index') }}">Test Catalog</a></li>
                            <li><a href="{{ route('lab.index') }}"
                                    class="{{ request()->routeIs('lab.*') ? 'active' : '' }}">Test Orders</a></li>
                            <li><a href="{{ route('lab.results.index') }}">Results</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('pharmacy.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-pill"></i><span>Pharmacy</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('pharmacy.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('pharmacy.medicines.index') }}">Medicines</a></li>
                            <li><a href="{{ route('pharmacy.inventory.index') }}">Batches</a></li>
                            <li><a href="{{ route('pharmacy.index') }}">Sales</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('billing.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-receipt"></i><span>Billing</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('billing.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('billing.index') }}"
                                    class="{{ request()->routeIs('billing.*') ? 'active' : '' }}">Invoices</a></li>
                            <li><a href="{{ route('billing.payments.index') }}">Payments</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('reports.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-report-analytics"></i><span>Reports</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('reports.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('reports.financial') }}">Revenue</a></li>
                            <li><a href="{{ route('reports.summary') }}">OPD/IPD Summary</a></li>
                            <li><a href="{{ route('reports.doctor_performance') }}">Doctor Performance</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('activity.index') }}"><i class="ti ti-activity"></i><span>Activity
                                Logs</span></a></li>
                @endif

                <!-- 3. DOCTOR FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Doctor'))
                    <li class="menu-title"><span>Clinical Workflow</span></li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('appointments.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-calendar"></i><span>Appointments</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('appointments.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('appointments.index') }}">Today</a></li>
                            <li><a href="{{ route('appointments.index') }}">Upcoming</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="{{ route('doctor.schedule.index') }}"
                            class="{{ request()->routeIs('doctor.schedule.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-calendar"></i><span>My Schedule</span>
                        </a>
                    </li>
                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('patients.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-users"></i><span>Patients</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('patients.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('patients.index') }}">OPD Patients</a></li>
                            <li><a href="{{ route('patients.index') }}">IPD Patients</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('clinical.consultations.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-stethoscope"></i><span>Consultations</span><span
                                class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('clinical.consultations.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('appointments.create') }}">New</a></li>
                            <li><a href="{{ route('visits.index') }}">History</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('clinical.prescriptions.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-prescription"></i><span>Prescriptions</span><span
                                class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('clinical.prescriptions.*') ? 'display: block;' : 'display: none;' }}">
                            <!-- Creation happens within a Consultation context -->
                            <li><a href="{{ route('clinical.prescriptions.index') }}">History</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('lab.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-microscope"></i><span>Lab</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('lab.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('lab.create') }}">Order Tests</a></li>
                            <li><a href="{{ route('lab.results.index') }}">View Results</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('ipd.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-bed"></i><span>IPD</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('ipd.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('ipd.index') }}">My Admissions</a></li>
                            <li><a href="{{ route('ipd.rounds.index') }}">Rounds</a></li>
                        </ul>
                    </li>
                @endif

                <!-- 4. RECEPTIONIST FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Receptionist'))
                    <li class="menu-title"><span>Front Desk</span></li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('appointments.*') || request()->routeIs('appointments.booking.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-calendar-plus"></i><span>Appointments</span><span
                                class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('appointments.*') || request()->routeIs('appointments.booking.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('appointments.booking.index') }}">Smart Booking</a></li>
                            <li><a href="{{ route('appointments.index') }}">Today List</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('patients.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-user-plus"></i><span>Patients</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('patients.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('patients.create') }}">Register Patient</a></li>
                            <li><a href="{{ route('patients.index') }}">Search Patient</a></li>
                        </ul>
                    </li>

                    @if (auth()->user()->hasPermission('view_billing') || auth()->user()->hasPermission('create_invoices'))
                        <li class="submenu">
                            <a href="#" class="{{ request()->routeIs('billing.*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-receipt"></i><span>Billing</span><span class="menu-arrow"></span>
                            </a>
                            <ul style="{{ request()->routeIs('billing.*') ? 'display: block;' : 'display: none;' }}">
                                @if (auth()->user()->hasPermission('view_billing'))
                                    <li><a href="{{ route('billing.index') }}">Invoices</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('create_invoices'))
                                    <li><a href="{{ route('billing.create') }}">Generate Invoice</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif

                <!-- 5. NURSE FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Nurse'))
                    <li class="menu-title"><span>Nursing Station</span></li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('ipd.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-bed"></i><span>IPD</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('ipd.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('ipd.index') }}">Admitted Patients</a></li>
                            <li><a href="{{ route('ipd.bed_status') }}">Bed Status</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('vitals.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-heart-rate-monitor"></i><span>Vitals</span><span
                                class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('vitals.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('vitals.record') }}">Record</a></li>
                            <li><a href="{{ route('vitals.history') }}">History</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('nursing.notes.index') }}"><i class="ti ti-notebook"></i><span>Nursing
                                Notes</span></a></li>
                @endif

                <!-- 6. LAB TECHNICIAN FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Lab Technician'))
                    <li class="menu-title"><span>Laboratory</span></li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('lab.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-test-pipe"></i><span>Lab Orders</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('lab.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('lab.index') }}">Pending</a></li>
                            <li><a href="{{ route('lab.index') }}">In Progress</a></li>
                            <li><a href="{{ route('lab.index') }}">Completed</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('lab.results.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-report-medical"></i><span>Results</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('lab.results.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('lab.results.index') }}">Browse Results</a></li>
                        </ul>
                    </li>
                @endif

                <!-- 7. PHARMACIST FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Pharmacist'))
                    <li class="menu-title"><span>Pharmacy</span></li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('pharmacy.prescriptions.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-prescription"></i><span>Prescriptions</span><span
                                class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('pharmacy.prescriptions.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('pharmacy.prescriptions.index') }}">Pending</a></li>
                            <li><a href="{{ route('pharmacy.prescriptions.index') }}">Fulfilled</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('pharmacy.inventory.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-box"></i><span>Inventory</span><span class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('pharmacy.inventory.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('pharmacy.medicines.index') }}">Medicines</a></li>
                            <li><a href="{{ route('pharmacy.inventory.index') }}">Batches</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#"
                            class="{{ request()->routeIs('pharmacy.index') || request()->routeIs('pharmacy.create') ? 'active subdrop' : '' }}">
                            <i class="ti ti-shopping-cart"></i><span>Sales</span><span class="menu-arrow"></span>
                        </a>
                        <ul
                            style="{{ request()->routeIs('pharmacy.index') || request()->routeIs('pharmacy.create') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('pharmacy.create') }}">New Sale</a></li>
                            <li><a href="{{ route('pharmacy.index') }}">History</a></li>
                        </ul>
                    </li>
                @endif

                <!-- 8. ACCOUNTANT FLOW -->
                @if (auth()->check() && auth()->user()->hasRole('Accountant'))
                    <li class="menu-title"><span>Finance</span></li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('billing.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-file-invoice"></i><span>Invoices</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('billing.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('billing.index') }}">Pending</a></li>
                            <li><a href="{{ route('billing.index') }}">Paid</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('payments.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-cash"></i><span>Payments</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('payments.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('payments.cash') }}">Cash</a></li>
                            <li><a href="{{ route('payments.digital') }}">Digital</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="#" class="{{ request()->routeIs('reports.*') ? 'active subdrop' : '' }}">
                            <i class="ti ti-chart-pie"></i><span>Reports</span><span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->routeIs('reports.*') ? 'display: block;' : 'display: none;' }}">
                            <li><a href="{{ route('reports.financial') }}">Revenue</a></li>
                            <li><a href="{{ route('reports.tax') }}">Tax</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- Sidenav Menu End -->
