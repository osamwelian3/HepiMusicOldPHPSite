@extends('layouts.main')

@section('content')
<div class="login-box">
    <div class="white-box login_custom_box">
        <form method="POST" action="{{ route('login') }}" class="form-horizontal form-material" id="loginform">
            @csrf
            <h3 class="box-title m-b-20">Login</h3>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="checkbox checkbox-primary pull-left p-t-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember" style="color: black;">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                        {{ __('Login') }}
                    </button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <a href="{{ Route('forgot.password.get') }}" id="to-recover" class="text-dark pull-left" style="color: black !important;"><i class="fa fa-lock m-r-5"></i> Forgot password ?</a>
                    <a href="{{ Route('register') }}" id="to-recover" class="text-dark pull-right" style="color: black !important;"><i class="fa fa-plus m-r-5"></i> Register</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection