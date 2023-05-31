@php
    $version = time();
    $baseUrl = asset('backend')."/";
    $loginUser = Auth::user();
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home</title>

    {{-- <link href="{{ $baseUrl }}bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    {{-- <link href="{{ $baseUrl }}bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet"> --}}
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
    <link rel="stylesheet" type="text/css" href="{{ $baseUrl }}components/green-audio-player/dist/css/green-audio-player.min.css">
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-nabvar" style="padding: 0 !important;">
            <a class="navbar-brand" href="{{ route('home') }}" style="padding: 0;">
                <img src="{{ $baseUrl }}images/logo-1.JPEG" alt="Logo" style="width: 80px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    {{-- <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li> --}}
                    @if (!$loginUser)
                        <li class="nav-item">
                            <a class="nav-link link-black" href="{{ route('register') }}">Signup</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-black" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link link-black" href="{{ route('songs.liked') }}">Liked</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-black" href="{{ route('logout') }}">Logout</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        @yield('content')
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
    <script src="{{ $baseUrl }}components/green-audio-player/dist/js/green-audio-player.js"></script>

    <script type="text/javascript">
        var _token = "{{ csrf_token() }}";
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