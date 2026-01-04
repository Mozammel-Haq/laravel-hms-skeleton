<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Dashboard - Medical & Hospital - Bootstrap 5 Admin Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">

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

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">

</head>

<body>

    <main class="main-wrapper">

        <x-layout.header />
        <x-layout.sidebar />
        <div class="page-wrapper">

            {{ $slot }}
            <x-layout.footer />

        </div>
    </main>


    <!-- jQuery -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- Simplebar JS -->
    <script src="{{ asset('assets') }}/plugins/simplebar/simplebar.min.js" type="text/javascript"></script>
    <!-- Chart JS -->
    <script src="{{ asset('assets') }}/plugins/apexchart/apexcharts.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/chart-data.js" type="text/javascript"></script>
    <!-- Daterangepikcer JS -->
    <script src="{{ asset('assets') }}/js/moment.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <!-- Datetimepicker JS -->
    <script src="{{asset('assets')}}/js/moment.min.js" type="3a68d12e02a3c1f0a6cad4fd-text/javascript"></script>
    <script src="{{asset('assets')}}/js/bootstrap-datetimepicker.min.js" type="3a68d12e02a3c1f0a6cad4fd-text/javascript"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets') }}/js/script.js" type="text/javascript"></script>

    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" defer></script>
</body>

</html>
