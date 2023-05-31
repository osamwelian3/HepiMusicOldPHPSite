@extends('layouts.main')

@section('content')
<div class="login-box">
    <div class="white-box login_custom_box">
        <form method="POST" class="form-horizontal form-material" id="loginform" action="{{ Route('forgot.password.get') }}">
            @csrf
            <h3 class="box-title m-b-20">Recover Password</h3>
            <div class="form-group ">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <a href="{{ Route('login') }}" id="to-recover" class="text-dark pull-left" style="color: black !important;"><i class="fa fa-lock m-r-5"></i> Login</a>
                    <a href="{{ Route('register') }}" id="to-recover" class="text-dark pull-right" style="color: black !important;"><i class="fa fa-plus m-r-5"></i> Register</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection