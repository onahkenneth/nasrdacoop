<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <title>NASRDA COOPERATIVE :: LOGIN</title>
    <meta content="Admin Dashboard" name="description">
    <meta content="ThemeDesign" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- App Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}"><!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
    </div><!-- Begin page -->
    
    <div class="account-pages">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div>
                        <!-- <img src="assets/images/logo_dark.png" height="28" alt="logo"></a> -->
                        <h4>LOGO HERE</h4>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="p-2">
                                <h4 class="text-muted float-right font-18 mt-4">Sign In</h4>
                                <div>
                                    <!-- <img src="assets/images/logo_dark.png" height="28" alt="logo"></a> -->
                                    <h4>NASRDA COOP</h4>
                                </div>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">

                                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">

                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">

                                            <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12"><button
                                                class="btn btn-primary btn-block waves-effect waves-light"
                                                type="submit">Log In</button></div>
                                    </div>
                                    <div class="form-group m-t-10 mb-0 row">
                                        <div class="col-sm-7 m-t-20">
                                            @if (Route::has('password.request'))
                                                <a class="text-muted" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end row -->
        </div>
    </div><!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script><!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
