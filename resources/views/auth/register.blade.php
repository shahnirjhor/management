<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
        <title>@lang('Register') | SSMS </title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}" />
        <link href="{{ asset('assets/css/frontend.css') }}" rel="stylesheet">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  --}}
	 {!! NoCaptcha::renderJs() !!}

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
                    <p class="login-box-msg m-0 p-0">@lang('Register in to start your session')</p>
                    <br>
                    <form method="POST" action="{{ route('student-register.store') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="E-Mail Address">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }} ">
                            <div class="col-md-12" style="">
                                {!! app('captcha')->display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-block btn-primary">
                                    @lang('Register')
                                </button>
                            </div>
                        </div>

                        <p class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-info btn-block"><span>Already Register ?</span> Login Now</a>
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




{{--  @extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection  --}}
