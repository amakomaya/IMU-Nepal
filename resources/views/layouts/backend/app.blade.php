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
    <link href="{{ asset('css/nepali.datepicker.v3.5.min.css') }}" rel="stylesheet" type="text/css"/>
    @php
        $metaRole = auth()->user()->role;
        $metaPermission = implode(",", auth()->user()->getPermissionNames()->toArray());
        if (auth()->user()->role == 'healthworker'){
            $healthWorker = \App\Models\OrganizationMember::where('token', Auth::user()->token)->first();
            $metaRole = $healthWorker->role;
            $hospital = \App\Models\Organization::where('hp_code', $healthWorker->hp_code)->first();
            if($hospital) {
              $federalInfo= json_encode(["province_id" => $hospital->province_id, "district_id" => $hospital->district_id, "municipality_id" => $hospital->municipality_id]);
              $province=$hospital->province_id;
              $h_type = $hospital->hospital_type;
            }

        }
        if (auth()->user()->role == 'healthpost'){
            $hospital = \App\Models\Organization::where('token', Auth::user()->token)->first();
            $federalInfo= json_encode(["province_id" => $hospital->province_id, "district_id" => $hospital->district_id, "municipality_id" => $hospital->municipality_id]);
            $h_type = $hospital->hospital_type;
        }
        $permission_id = session()->get('permission_id');
        
    @endphp
    <meta name="user-role" content="{{ $metaRole ?? '' }}">
    <meta name="hospital-type" content="{{ $h_type ?? '' }}">
    <meta name="user-permission" content="{{  $metaPermission }}">
    <meta name="permission-id" content="{{  $permission_id ?? '' }}">
    <meta name="federal-info" content="{{  $federalInfo ?? json_encode(['province_id' => '', 'district_id' => '', 'municipality_id' => '']) }}">
    <meta name="user-session-token" content="{{  Request::session()->get('user_token') }}">
    <meta name="user-role-token" content="{{ \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) }}">
    <script src="{{ asset('js/sortable.js') }}"></script>

    <style>
        .sidebar{
            top: 0;
            position: fixed;
            overflow-y: scroll;
            overflow-x: hidden;
            height: 90%;
        }
    </style>
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
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/index') }}">
                        {{ config('app.name') }} </a>
                    @guest
                    <!-- // -->
                    @else
                    <a class="navbar-brand navbar-brand-small"><small class="text-primary"></small></a>
                    @endif
            </div>
            <div class="navbar-nav" style="margin: 15px 0px 15px 100px">
                <span title="{{ \App\User::getAppRole() }}"><strong>Welcome, </strong> You are logged in as
                    @if(strlen(\App\User::getAppRole()) > 30)
                    {{ substr(\App\User::getAppRole(), 0, 28) . "..." }}
                @else
                    {!! \App\User::getAppRole() !!}
                    @endif
                </span>
            </div>
            <?php
                $temp_name = session()->get('temp_name') ?? null;
            ?>
            <div class="navbar-right" style="margin: 0px 10px">
                <li class="nav navbar-nav" style="margin: 10px;">
                    <form action="{{ route("refresh-page") }}" method="post">
                    @csrf
                    <input type="hidden" name="temp_name" value="{{$temp_name}}" id="temp_name">
                    <button type="submit"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Page</button>
                    </form>
                </li>
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
                          <li><a href="/admin/profile">
                                  <i class="fa fa-edit" style="font-size:24px"></i>
                                  Edit Profile</a></li>
                            <hr style="height:2px;border-width:0; margin:15px 0 0 0;color:gray;background-color:gray">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>

                                    {{ trans('sidebar.logout') }} ({{ Auth::user()->username }})
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                      </li>
                    </ul>
                </li>
            </div>

            {{-- </ul> --}}
            <div class="navbar-default sidebar" role="navigation">
                <nav class="sidebar-nav navbar-collapse">
                    @include ('layouts.backend.navigation.list')
                </nav>
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div style="margin: 40px 0px 0px 0px">
            @yield('content')

        </div>

    </div>
    <!-- /#wrapper -->

    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}


    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/nepali.datepicker.v3.5.min.js') }}"></script>

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