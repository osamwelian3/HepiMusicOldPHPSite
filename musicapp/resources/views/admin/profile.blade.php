@extends('layouts.app')
@section('content')
<div id="page-wrapper" style="min-height: 257px;">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Profile</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="active">Profile</li>
                </ol>
            </div>            
        </div>
        
        <div class="white-box">
            <div class="row">
                <div class="col-md-6">
                    <form id="profile_form" method="post" class="ps-3 pe-3" action="{{route('updateProfile')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="first_name" class="control-label">First Name:</label>
                                <input id="first_name" type="text" class="form-control first_name" name="first_name" placeholder="First Name">
                                <span class="invalid-feedback error-first_name" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="last_name" class="control-label">Last Name:</label>
                                <input id="last_name" type="text" class="form-control last_name" name="last_name" placeholder="Last Name">
                                <span class="invalid-feedback error-last_name" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="email" class="control-label">Email:</label>
                                <input id="email" type="text" class="form-control email" name="email" placeholder="Email">
                                <span class="invalid-feedback error-email" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="phone" class="control-label">Phone:</label>
                                <input id="phone" type="text" class="form-control phone" name="phone" placeholder="Phone">
                                <span class="invalid-feedback error-phone" role="alert"><strong></strong></span>
                            </div>
                            <div class="col-md-12 mt-3">                            
                                <button type="submit" class="btn btn-success waves-effect waves-light pull-right">Update</button>
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
    var updateProfileUrl = "{{route('updateProfile')}}";
    var profileDetailUrl = "{{ route('profileDetail') }}";
</script>
@endsection

@section('pagejs')
<script src="{{addPageJsLink('profile.js')}}"></script>
@endsection