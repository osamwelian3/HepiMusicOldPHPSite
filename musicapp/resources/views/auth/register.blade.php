@extends('layouts.main')

@section('content')
<div class="login-box register_form">
    <div class="white-box login_custom_box">
        <div class="block-title">
            <div class="block-options pull-right">                
                <a href="{{ Route('login') }}" title="Back to login" class="text-dark pull-right back_login" style="color: black !important;">
                    <b><i class="fa fa-arrow-left"></i></b>
                </a>
            </div>
            <h3 class="box-title m-b-20">Register</h3>
        </div>
        <form method="POST" action="{{ route('register') }}" class="form-horizontal form-material" id="registerForm" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="first_name" type="text" class="form-control first_name @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="last_name" type="text" class="form-control last_name @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="phone" type="text" class="form-control phone @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Phone">

                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="email" type="email" class="form-control email @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="col-xs-12">
                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Password Confirmation">

                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-12 text-center m-t-20">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                            Register
                        </button>
                    </div>
                </div>                
            </div>
        </form>
    </div>
</div>
@endsection

@section('pagejs')
<!-- <script src="{{addPageJsLink('register.js')}}"></script> -->
@endsection