@extends('layouts.app')
@section('content')
<div id="page-wrapper" style="min-height: 257px;">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Change Password</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="active">Change Password</li>
                </ol>
            </div>            
        </div>
        
        <div class="white-box">
            <div class="row">
                <div class="col-md-6">
                    <form id="password_form" method="post" class="ps-3 pe-3" action="{{route('password.update')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">                            
                            <div class="col-md-12 mb-3">
                                <label for="old_password" class="control-label">Old Password:</label>
                                <input id="old_password" type="password" class="form-control" name="old_password" placeholder="Old Password">
                                <span class="invalid-feedback error-old_password" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="password" class="control-label">New Password:</label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="New Password">
                                <span class="invalid-feedback error-password" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="password_confirmation" class="control-label">Repeat Password:</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password">
                                <span class="invalid-feedback error-password_confirmation" role="alert"><strong></strong></span>
                            </div>

                            <div class="col-md-12 mt-3">                            
                                <button type="submit" class="btn btn-success waves-effect waves-light pull-right">Save changes</button>
                            </div>               
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var updateProfileUrl = "{{route('passwords.update')}}";
</script>
@endsection

@section('pagejs')
<script src="{{addPageJsLink('password.js')}}"></script>
@endsection