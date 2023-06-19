<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
        <title>@lang('Log in') | SSMS </title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}" />
        <link href="{{ asset('assets/css/frontend.css') }}" rel="stylesheet">
        @if(session('locale') == 'ar')
            <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
        @else
            <link href="{{ asset('assets/plugins/alertifyjs/css/themes/bootstrap.min.css') }}" rel="stylesheet">
        @endif
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a class="h1"><span class="identColor"><b>S</b></span>SMS</a>
                </div>
                <div class="card-body">
                    @if (session()->has('flash_notification.success'))
                        <div class="alert alert-success" role="alert">{!! session('flash_notification.success') !!}</div>
                    @endif
                    @if (\Session::has('message'))
                        <div class="alert alert-info" role="alert">
                            <ul>
                                <li>{!! \Session::get('message') !!}</li>
                            </ul>
                        </div>
                    @endif
                    <p class="login-box-msg m-0 p-0">@lang('Sign in to start your session')</p>
                    <br>
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="@lang('Email')" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('Password')" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">
                                        @if(session('locale') == 'ar')
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif
                                        @lang('Remember Me')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-block btn-primary"> <i class="fas fa-sign-in-alt mr-2"></i> @lang('Log in')</button>
                        </div>

                        <p class="text-center">
                            <a href="{{ route('register') }}" class="btn btn-info btn-block"><span>New Student?</span> Register Now</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
        @if(session('locale') == 'ar')
            <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        @else
            <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        @endif
        <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom/login.js') }}"></script>
    </body>
</html>
