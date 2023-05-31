@extends('layouts.app')
@section('content')
<div id="page-wrapper" style="min-height: 257px;">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Referral Users</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="active">Referral Users</li>
                </ol>
            </div>            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="referral-listTable" class="table table-hover">
                            <thead>
                                <tr>                                    
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>                                    
                                    <th>Phone</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
	var apiUrl = "{{ route('users.list') }}";
    var referralUsersUrl = "{{ route('users.referral-list') }}";
</script>
@endsection

@section('pagejs')
<script src="{{addPageJsLink('user.js')}}"></script>
@endsection