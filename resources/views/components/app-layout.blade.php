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
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/tabler-icons/tabler-icons.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/dataTables.bootstrap5.min.css">
    @stack('styles')
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">

</head>

<body>

    <main class="main-wrapper">

        <x-layout.header />
        <x-layout.sidebar />
        <div class="page-wrapper">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    {{ session('error') }}
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
    <script src="{{ asset('assets') }}/js/doctors.js"></script>
    <script src="{{ asset('assets') }}/js/script.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" defer></script>

</body>

</html>
