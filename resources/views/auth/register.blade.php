<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register Illustration Form | Preclinic - Medical & Hospital - Bootstrap 5 Admin Template</title>
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
                            <img src="{{ asset('assets') }}/img/auth/reg-illustration-img.png"
                                alt="log-illustration-img-01" class="img-fluid img1">
                        </div>
                    </div> <!-- end row-->

                    <div class="col-lg-6 col-md-12 col-sm-12 position-relative">
                        <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100 py-3">
                            <div class="col-md-8 mx-auto">

                                <form method="POST" action="{{ route('register') }}"
                                    class="d-flex justify-content-center align-items-center">
                                    @csrf
                                    <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                                        <div class=" mx-auto mb-4 text-center">
                                            <img src="{{ asset('assets') }}/img/logo.svg" class="img-fluid"
                                                alt="Logo">
                                        </div>
                                        <div class="card border-1 p-lg-3 shadow-md rounded-3 shadow-sm  mb-4">
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <h5 class="mb-1 fs-20 fw-bold">Register</h5>
                                                    <p class="mb-0">Please enter your details to create account</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Full Name') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-end-0 bg-white">
                                                            <i class="ti ti-user fs-14 text-dark"></i>
                                                        </span>
                                                        <input type="text" value=""
                                                            class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                                            placeholder="Enter Name" name="name"
                                                            value="{{ old('name') }}" required autocomplete="name"
                                                            autofocus>

                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-end-0 bg-white">
                                                            <i class="ti ti-mail fs-14 text-dark"></i>
                                                        </span>
                                                        <input type="text"
                                                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                                            name="email" value="{{ old('email') }}" required
                                                            autocomplete="email" placeholder="Enter Email Address">


                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Password</label>
                                                    <div class="position-relative">
                                                        <div
                                                            class="pass-group input-group position-relative border rounded">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti ti-lock text-dark fs-14"></i>
                                                            </span>
                                                            <input type="password"
                                                                class="pass-input form-control ps-0 border-0 @error('password') is-invalid @enderror"
                                                                name="password" required autocomplete="new-password"
                                                                placeholder="****************">

                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            <span class="input-group-text bg-white border-0">
                                                                <i
                                                                    class="ti toggle-password ti-eye-off text-dark fs-14"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Confirm Password</label>
                                                    <div class="position-relative">
                                                        <div
                                                            class="pass-group input-group position-relative border rounded">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti ti-lock text-dark fs-14"></i>
                                                            </span>
                                                            <input id="password-confirm" type="password"
                                                                class="pass-inputs form-control ps-0 border-0"
                                                                placeholder="****************"
                                                                name="password_confirmation" required
                                                                autocomplete="new-password">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i
                                                                    class="ti toggle-passwords ti-eye-off text-dark fs-14"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check form-check-md mb-0">
                                                            <input class="form-check-input" id="remember_me"
                                                                type="checkbox">
                                                            <label for="remember_me"
                                                                class="form-check-label mt-0 text-dark">I agree to the
                                                                <a href="terms-and-conditions.html"
                                                                    class="text-decoration-underline text-primary">
                                                                    Terms of Service</a> & <a
                                                                    href="privacy-policy.html"
                                                                    class="text-decoration-underline text-primary">Privacy
                                                                    Policy </a></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <button type="submit" class="btn btn-primary text-white w-100">
                                                        {{ __('Register') }}
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
                                                    <h6 class="fw-normal fs-14 text-dark mb-0">Already have an account
                                                        yet?
                                                        <a href="{{ route('login') }}" class="hover-a"> Login</a>
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
    <script src="{{asset('assets')}}/js/jquery-3.7.1.min.js" type="4db7f0863ce025f613364f40-text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('assets')}}/js/bootstrap.bundle.min.js" type="4db7f0863ce025f613364f40-text/javascript"></script>

    <!-- Main JS -->
    <script src="{{asset('assets')}}/js/script.js" type="4db7f0863ce025f613364f40-text/javascript"></script>

    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" data-cf-settings="4db7f0863ce025f613364f40-|49" defer>
    </script>
</body>

</html>
