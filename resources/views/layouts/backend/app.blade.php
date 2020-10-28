<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} Admin</title>

    <!-- Styles -->
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- MetisMenu CSS -->
    <link href="{{ asset('bower_components/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('bower_components/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

    <meta name="user-role" content="{{ Auth::user()->role }}">

@yield('style')
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name') }} </a>
                    @guest
                    <!-- // -->
                    @else
                    <a class="navbar-brand navbar-brand-small"><small class="text-primary"></small></a>
                    @endif

            </div>
            <!-- /.navbar-header -->

            {{-- <ul class="nav navbar-nav navbar-top-links navbar-right"> --}}
            <div class="navbar-right">
                <li class="nav navbar-nav sl-nav messageInfo" style="margin: 10px;">
                    <i class="fa fa-envelope-o"> : </i>
                    <ul>
                        <li><b>Messages @include('messenger.unread-count')</b> <i class="fa fa-angle-down" aria-hidden="true"></i>
                            <div class="triangle"></div>
                            <ul>
                                <li><a href="/admin/messages">Messages</a></li>
                                <li><a href="/admin/messages/create">Create New Message</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav navbar-nav sl-nav" style="margin: 10px;">
                    @lang('app.language') :
                    <ul>
                    <li><b>{{ (app()->getLocale() == 'np') ? 'नेपाली' : 'English' }}</b> <i class="fa fa-angle-down" aria-hidden="true"></i>
                        <div class="triangle"></div>
                        <ul>
                          <li><a href="{{ url('locale/en') }}"><i class="sl-flag flag-usa"></i> <span>English</span></a></li>
                        <li><a href="{{ url('locale/np') }}"><i class="sl-flag flag-np"></i> <span>नेपाली</span></a></li>
                        </ul>
                      </li>
                    </ul>
                </li>
               <li class="nav navbar-nav sl-nav userinfo" style="margin: 10px;">
                    <i class="fa fa-user"> : </i>
                    <ul>
                    <li><b>{{ \Auth::user()->username }}</b> <i class="fa fa-angle-down" aria-hidden="true"></i>
                        <div class="triangle"></div>
                        <ul>
                          <li>{{ \App\User::getAppRole() }}</li>
                          <li><a href="/admin/profile">Edit Profile</a></li>
                        </ul>
                      </li>
                    </ul>
                </li>
                <ul class="nav navbar-nav" style="margin-top: -5px;">
            </div>

            {{-- </ul> --}}
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    @include ('layouts.backend.navigation.list')
            </div>
            <!-- /.navbar-static-side -->
        </nav>


        @yield('content')
    </div>
    <!-- /#wrapper -->

    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}


    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('dist/js/sb-admin-2.js') }}"></script>

    <script src="{{ asset('js/custom.js') }}"></script>

@yield('script')
</body>
</html>