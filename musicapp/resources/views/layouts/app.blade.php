@php
    $version=20220813;
    $nameUrl = Request::segment(1);
    $baseUrl = asset('backend')."/";
    $loginUser = Auth::user();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Web') }}</title>    
    <link href="" rel="shortcut icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ $baseUrl }}bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $baseUrl }}bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" /> --}}
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    
    <link href="{{ $baseUrl }}components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ $baseUrl }}components/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ $baseUrl }}components/html5-editor/bootstrap-wysihtml5.css" rel="stylesheet"/>
    <!-- Calendar CSS -->
    <link rel="stylesheet" href="{{ $baseUrl }}components/fullcalendar/fullcalendar.css?{{ $version }}">    
    <!-- Menu CSS -->
    <link href="{{ $baseUrl }}components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{ $baseUrl }}css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ $baseUrl }}css/toastr.min.css?{{ $version }}" rel="stylesheet">
    <link href="{{ $baseUrl }}css/jquery.dataTables.min.css">

    <link href="{{ $baseUrl }}components/custom-select/custom-select.css" rel="stylesheet">
    <link href="{{ $baseUrl }}components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="{{ $baseUrl }}components/multiselect/css/multi-select.css" rel="stylesheet">
    <link href="{{ $baseUrl }}css/select2.min.css?{{ $version }}" rel="stylesheet">
    <link href="{{ $baseUrl }}css/style.css" rel="stylesheet">
    
    <!-- color CSS -->
    <link href="{{ $baseUrl }}css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- DateTime Picker CSS -->
    <link href="{{ $baseUrl }}css/bootstrap-datetimepicker.css" id="theme" rel="stylesheet">
    <link href="{{ $baseUrl }}components/bootstrap-datepicker/bootstrap-datepicker.min.css" id="theme" rel="stylesheet">
    <!-- jquery ui for autocomplete -->
    <link href="{{ $baseUrl }}css/jquery-ui.min.css" rel="stylesheet">
    <link href="{{ $baseUrl }}css/jquery.timepicker.min.css" rel="stylesheet">
    <!-- other CSS -->    
    <link href="{{ $baseUrl }}css/custom.css?{{ $version }}" rel="stylesheet">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="LoadingImage" style="display: none;">
            <img src="{{ $baseUrl }}images/message-loader.gif" />
        </div>
    <div id="wrapper">
        <!-- Top Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> 

                <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>

                <div class="top-left-part" style="background-color: transparent!important; text-align: center;">
                    <a class="logo" href="{{ route('dashboard') }}">
                        {{-- <b> --}}
                            <img src="{{ $baseUrl }}images/logo-3.png" alt="Logo" style="width: 45px; margin-top: 5px;">
                        {{-- </b> --}}
                        {{-- <span class="hidden-xs">
                            <img src="{{ $baseUrl }}images/eliteadmin-text.png" alt="home" />
                        </span> --}}
                    </a>
                </div>

                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>                   
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">                    
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <b class="hidden-xs">{{ $loginUser->first_name }} {{ $loginUser->last_name }}</b> 
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <a href="{{ route('profile') }}"><i class="fa fa-user"></i> Profile</a>
                            </li>
                            <li>
                                <a href="{{ route('change-password') }}"><i class="fa fa-lock"></i> Change Password</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">                    
                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="fa fa-dashboard"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    @if ($loginUser->role_type == 1)
                        <li>
                            <a href="{{ route('categories') }}" class="waves-effect">
                                <i class="fa fa-list"></i>
                                <span class="hide-menu">Categories</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('songs') }}" class="waves-effect">
                                <i class="fa fa-music"></i>
                                <span class="hide-menu">Songs</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-small-cap" style="text-align: center;">- - - - - - - - - - - - -</li>
                    <li>
                        <a href="{{ route('profile') }}" class="waves-effect">
                            <i class="fa fa-user"></i>
                            <span class="hide-menu">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('change-password') }}" class="waves-effect">
                            <i class="fa fa-lock"></i>
                            <span class="hide-menu">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}" class="waves-effect">
                            <i class="fa fa-sign-out"></i>
                            <span class="hide-menu">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
        <!-- Page Content -->       
        @yield('content')
        <!-- /#page-wrapper -->
    </div>

    @yield('modal')

    <script src="{{ $baseUrl }}js/jquery_v3_6_0_full.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ $baseUrl }}bootstrap/dist/js/tether.min.js"></script>
    <script src="{{ $baseUrl }}bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ $baseUrl }}bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{ $baseUrl }}components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <script src="{{ $baseUrl }}components/bootstrap-switch/bootstrap-switch.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="{{ $baseUrl }}js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="{{ $baseUrl }}js/waves.js"></script>
    <!--MultiSelect JavaScript -->
    <!-- Custom Theme JavaScript -->
    <script src="{{ $baseUrl }}js/custom.min.js"></script>

    {{-- <script src="{{ $baseUrl }}components/datatables/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    {{-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script> --}}
    {{-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script> --}}
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    
    <script src="{{ $baseUrl }}components/styleswitcher/jQuery.style.switcher.js"></script>
    
    <script src="{{ $baseUrl }}components/tinymce/tinymce.min.js"></script>

    <script src="{{ $baseUrl }}js/jquery-ui.min.js"></script>

    <!-- Calendar JavaScript -->
    <script src='{{ $baseUrl }}components/perfect-scrollbar/perfect-scrollbar.min.js'></script>
    <script src="{{ $baseUrl }}components/moment/moment.js"></script>    
    <script src='{{ $baseUrl }}components/fullcalendar/fullcalendar.min.js'></script>

    <!-- require js for date-js  -->
    {{-- <script src="{{ $baseUrl }}js/require.js"></script> --}}
    <!--DateTime Picker Js -->
    <script src="{{ $baseUrl }}js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{ $baseUrl }}components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{ $baseUrl }}js/jquery.timepicker.min.js"></script>    
    <script src="{{ $baseUrl }}js/toastr.min.js?{{ $version }}"></script>
    
    <script src="{{ $baseUrl }}js/select2.min.js?{{ $version }}"></script>
    <script src="{{ $baseUrl }}components/custom-select/custom-select.min.js?{{ $version }}"></script>
    <script src="{{ $baseUrl }}components/bootstrap-select/bootstrap-select.min.js?{{ $version }}"></script>
    <script src="{{ $baseUrl }}components/multiselect/js/jquery.multi-select.js?{{ $version }}"></script>

    <script src="{{ $baseUrl }}js/input-mask.js?{{ $version }}"></script>
    <script src="{{ $baseUrl }}js/custom_js.js?{{ $version }}"></script>

    <script type="text/javascript">
        var siteUrl = "{{asset('/')}}";
        var baseUrl = "{{asset('/')}}{{Request::segment(1)}}/";
        var nameUrl = "{{Request::segment(2)}}";        
        var _token = "{{ csrf_token() }}";    
        var timeZone = "{{ env('APP_TIMEZONE', 'UTC') }}";    
    </script>

    @if(Session::has('status'))
        <script type="text/javascript">
            showMessage("success","{{ Session::get('status') }}");
        </script>    
        @php Session::forget('status') @endphp
    @endif
    @if(Session::has('success'))
        <script type="text/javascript">
            showMessage("success","{{ Session::get('success') }}");
        </script>
        @php Session::forget('success') @endphp
    @endif
    @if(Session::has('error'))
        <script type="text/javascript">
            showMessage("error","{{ Session::get('error') }}");
        </script>
        @php Session::forget('error') @endphp
    @endif
    @if(Session::has('warning'))
        <script type="text/javascript">
            showMessage("warning","{{ Session::get('warning') }}");
        </script>
        @php Session::forget('warning') @endphp
    @endif

    @yield('js')
    @yield('pagejs')
        
</body>
</html>
