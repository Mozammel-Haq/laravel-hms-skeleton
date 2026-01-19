<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Dashboard - Medical & Hospital - Bootstrap 5 Admin Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.png">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/img/apple-icon.png">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets') }}/js/theme-script.js" type="text/javascript"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap-datetimepicker.min.css">

    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css"
        integrity="sha512-M5Kq4YVQrjg5c2wsZSn27Dkfm/2ALfxmun0vUE3mPiJyK53hQBHYCVAtvMYEC7ZXmYLg8DVG4tF8gD27WmDbsg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/tabler-icons/tabler-icons.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/dataTables.bootstrap5.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">
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
    @stack('styles')
</head>

<body>

    <main class="main-wrapper">

        <x-layout.header />
        <x-layout.sidebar />
        <div class="page-wrapper">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show m-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot }}
            <x-layout.footer />

        </div>
    </main>

    <!-- jQuery (MUST be first) -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS (after jQuery!) -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.min.js"></script>
    <!-- Other plugins -->
    <script src="{{ asset('assets') }}/plugins/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/apexcharts.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/chart-data.js"></script>
    <script src="{{ asset('assets') }}/js/moment.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Datatable JS -->
    <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/dataTables.bootstrap5.min.js"></script>
    <!-- Your custom scripts -->
    @stack('scripts')

    <script src="{{ asset('assets') }}/js/script.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" defer></script>

</body>

</html>
