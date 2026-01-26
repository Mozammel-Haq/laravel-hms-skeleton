<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Email | Preclinic - Medical & Hospital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.png">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/img/apple-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/tabler-icons/tabler-icons.min.css">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">

</head>

<body>

    <div class="main-wrapper auth-bg position-relative overflow-hidden">

        <div class=" position-relative z-1">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 bg-white">

                <div class="row h-100">

                    <!-- Left Side Illustration -->
                    <div class="col-lg-6 p-0 d-none d-lg-block">
                        <div
                            class="login-backgrounds bg-primary d-flex align-items-center justify-content-center h-100">
                            <img src="{{ asset('assets') }}/img/auth/email-verification-illustration-img.png"
                                alt="Verify Email Illustration" class="img-fluid" style="max-width: 80%;">
                        </div>
                    </div>

                    <!-- Right Side Content -->
                    <div class="col-lg-6 col-md-12 p-0">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="w-100 p-4" style="max-width: 500px;">

                                <div class="text-center mb-4">
                                    <img src="{{ asset('assets') }}/img/logo.svg" class="img-fluid" alt="Logo"
                                        style="height: 40px;">
                                </div>

                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-4">

                                        <div class="text-center mb-4">
                                            <div
                                                class="avatar avatar-xl bg-primary-subtle text-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center">
                                                <i class="ti ti-mail fs-1"></i>
                                            </div>
                                            <h4 class="fw-bold">Verify Your Email</h4>
                                            <p class="text-muted small">
                                                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                                            </p>
                                        </div>

                                        @if (session('status') == 'verification-link-sent')
                                            <div class="alert alert-success d-flex align-items-center mb-4"
                                                role="alert">
                                                <i class="ti ti-circle-check fs-5 me-2"></i>
                                                <div class="small">
                                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                                </div>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('verification.send') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                                <i class="ti ti-send me-1"></i> {{ __('Resend Verification Email') }}
                                            </button>
                                        </form>

                                        <div class="text-center">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-link text-decoration-none text-muted">
                                                    <i class="ti ti-logout me-1"></i> {{ __('Log Out') }}
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-center mt-4 text-muted small">
                                    &copy; {{ date('Y') }} Preclinic. All rights reserved.
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- Jquery JS -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('assets') }}/js/feather.min.js"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('assets') }}/plugins/simplebar/simplebar.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets') }}/js/script.js"></script>

</body>

</html>
