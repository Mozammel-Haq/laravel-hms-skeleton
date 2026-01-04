<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Illustration Form | Preclinic - Medical & Hospital - Bootstrap 5 Admin Template</title>
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

    <!-- Begin Wrapper -->
    <div class="main-wrapper auth-bg position-relative overflow-hidden">

        <div class="container-fuild position-relative z-1">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 bg-white">
                <!-- start row-->
                <div class="row">
                    <div class="col-lg-6 p-0">
                        <div
                            class="login-backgrounds d-lg-flex align-items-center justify-content-center d-none flex-wrap p-4 position-relative h-100 z-0">
                            <img src="{{ asset('assets') }}/img/icons/log-illustration-img-01.png"
                                alt="log-illustration-img-01" class="img-fluid img1">
                        </div>
                    </div> <!-- end row-->

                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100 py-3">
                            <div class="col-md-8 mx-auto">

                                <form action="{{ route('login') }}" method="POST"
                                    class="d-flex justify-content-center align-items-center">
                                    @csrf
                                    <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                                        <div class=" mx-auto mb-4 text-center">
                                            <img src="{{ asset('assets') }}/img/logo.svg" class="img-fluid"
                                                alt="Logo">
                                        </div>
                                        <div class="card border-1 p-lg-3 shadow-md rounded-3 mb-4">
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <h5 class="mb-1 fs-20 fw-bold">Sign In</h5>
                                                    <p class="mb-0">Please enter below details to access the dashboard
                                                    </p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Email Address') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-end-0 bg-white">
                                                            <i class="ti ti-mail fs-14 text-dark"></i>
                                                        </span>
                                                        <input type="text" value="{{ old('email') }}"
                                                            class="form-control @error('email') is-invalid @enderror border-start-0 ps-0"
                                                            placeholder="Enter Email Address" required
                                                            autocomplete="email" autofocus name="email">

                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Password') }}</label>
                                                    <div class="position-relative">
                                                        <div
                                                            class="pass-group input-group position-relative border rounded">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti ti-lock text-dark fs-14"></i>
                                                            </span>
                                                            <input type="password"
                                                                class="pass-input form-control @error('password') is-invalid @enderror ps-0 border-0"
                                                                name="password" placeholder="****************" required
                                                                autocomplete="current-password">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i
                                                                    class="ti toggle-password ti-eye-off text-dark fs-14"></i>
                                                            </span>
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check form-check-md mb-0">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="remember" id="remember_me"
                                                                {{ old('remember') ? 'checked' : '' }}>
                                                            <label for="remember_me"
                                                                class="form-check-label mt-0 text-dark">{{ __('Remember Me') }}</label>

                                                            <input class="form-check-input" type="checkbox"
                                                                name="remember" id="remember"
                                                                {{ old('remember') ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                    <div class="text-end">

                                                        @if (Route::has('password.request'))
                                                            <a class="text-danger"
                                                                href="{{ route('password.request') }}">
                                                                {{ __('Forgot Your Password?') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <button type="submit" class="btn btn-primary text-white w-100">
                                                        {{ __('Login') }}
                                                    </button>
                                                </div>
                                                <div class="login-or position-relative mb-3">
                                                    <span class="span-or">OR</span>
                                                </div>
                                                <div class="mb-3">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center flex-wrap">
                                                        <div class="text-center me-2 flex-fill">
                                                            <a href="javascript:void(0);"
                                                                class="br-10 p-1 btn btn-outline-light border d-flex align-items-center justify-content-center">
                                                                <img class="img-fluid m-1"
                                                                    src="{{ asset('assets') }}/img/icons/facebook-logo.svg"
                                                                    alt="Facebook">
                                                            </a>
                                                        </div>
                                                        <div class="text-center me-2 flex-fill">
                                                            <a href="javascript:void(0);"
                                                                class="br-10 p-1 btn btn-outline-light border d-flex align-items-center justify-content-center">
                                                                <img class="img-fluid m-1"
                                                                    src="{{ asset('assets') }}/img/icons/google-logo.svg"
                                                                    alt="Google">
                                                            </a>
                                                        </div>
                                                        <div class="text-center me-2 flex-fill">
                                                            <a href="javascript:void(0);"
                                                                class="br-10 p-1 btn btn-outline-light border d-flex align-items-center justify-content-center">
                                                                <img class="img-fluid m-1"
                                                                    src="{{ asset('assets') }}/img/icons/apple-logo.svg"
                                                                    alt="apple">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <h6 class="fw-normal fs-14 text-dark mb-0">Don’t have an account
                                                        yet?
                                                        <a href="{{ route('register') }}" class="hover-a">
                                                            Register</a>
                                                    </h6>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                </form>
                                <p class="text-dark text-center">Copyright &copy; 2025 - Preclinic.</p>
                            </div> <!-- end row-->
                        </div>
                    </div>
                </div>
                <!-- end row-->

            </div>
        </div>

    </div>
    <!-- End Wrapper -->


    <!-- jQuery -->
    <script src="{{asset('assets')}}/js/jquery-3.7.1.min.js" type="2732805a8c33ef7ba1c7249a-text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('assets')}}/js/bootstrap.bundle.min.js" type="2732805a8c33ef7ba1c7249a-text/javascript"></script>

    <!-- Main JS -->
    <script src="{{asset('assets')}}/js/script.js" type="2732805a8c33ef7ba1c7249a-text/javascript"></script>

    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" data-cf-settings="2732805a8c33ef7ba1c7249a-|49" defer>
    </script>
</body>

</html>
