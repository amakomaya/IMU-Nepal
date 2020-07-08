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
    @yield('style')
    <style>
        .pace {
            -webkit-pointer-events: none;
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .pace-inactive {
            display: none;
        }

        .pace .pace-progress {
            background: #ed053d;
            position: fixed;
            z-index: 2000;
            top: 0;
            right: 100%;
            width: 100%;
            height: 2px;
        }

        .pace .pace-progress-inner {
            display: block;
            position: absolute;
            right: 0px;
            width: 100px;
            height: 100%;
            box-shadow: 0 0 10px #ed053d, 0 0 5px #ed053d;
            opacity: 1.0;
            -webkit-transform: rotate(3deg) translate(0px, -4px);
            -moz-transform: rotate(3deg) translate(0px, -4px);
            -ms-transform: rotate(3deg) translate(0px, -4px);
            -o-transform: rotate(3deg) translate(0px, -4px);
            transform: rotate(3deg) translate(0px, -4px);
        }

        .pace .pace-activity {
            display: block;
            position: fixed;
            z-index: 2000;
            top: 15px;
            right: 15px;
            width: 14px;
            height: 14px;
            border: solid 2px transparent;
            border-top-color: #ed053d;
            border-left-color: #ed053d;
            border-radius: 10px;
            -webkit-animation: pace-spinner 400ms linear infinite;
            -moz-animation: pace-spinner 400ms linear infinite;
            -ms-animation: pace-spinner 400ms linear infinite;
            -o-animation: pace-spinner 400ms linear infinite;
            animation: pace-spinner 400ms linear infinite;
        }

        @-webkit-keyframes pace-spinner {
            0% { -webkit-transform: rotate(0deg); transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); }
        }
        @-moz-keyframes pace-spinner {
            0% { -moz-transform: rotate(0deg); transform: rotate(0deg); }
            100% { -moz-transform: rotate(360deg); transform: rotate(360deg); }
        }
        @-o-keyframes pace-spinner {
            0% { -o-transform: rotate(0deg); transform: rotate(0deg); }
            100% { -o-transform: rotate(360deg); transform: rotate(360deg); }
        }
        @-ms-keyframes pace-spinner {
            0% { -ms-transform: rotate(0deg); transform: rotate(0deg); }
            100% { -ms-transform: rotate(360deg); transform: rotate(360deg); }
        }
        @keyframes pace-spinner {
            0% { transform: rotate(0deg); transform: rotate(0deg); }
            100% { transform: rotate(360deg); transform: rotate(360deg); }
        }

    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .container {
            width: 1180px;
            margin-top: 3em;
        }
        #accordion .panel {
            border-radius: 0;
            border: 0;
            margin-top: 0px;
        }
        #accordion a {
            display: block;
            padding: 10px 15px;
            border-bottom: 1px solid #b42b2b;
            text-decoration: none;
        }
        #accordion .panel-heading a.collapsed:hover,
        #accordion .panel-heading a.collapsed:focus {
            background-color: #b42b2b;
            color: white;
            transition: all 0.2s ease-in;
        }
        #accordion .panel-heading a.collapsed:hover::before,
        #accordion .panel-heading a.collapsed:focus::before {
            color: white;
        }
        #accordion .panel-heading {
            padding: 0;
            border-radius: 0px;
            text-align: center;
        }
        #accordion .panel-heading a:not(.collapsed) {
            color: white;
            background-color: #b42b2b;
            transition: all 0.2s ease-in;
        }

        /* Add Indicator fontawesome icon to the left */
        #accordion .panel-heading .accordion-toggle::before {
            font-family: 'FontAwesome';
            content: '\f00d';
            float: left;
            color: white;
            font-weight: lighter;
            transform: rotate(0deg);
            transition: all 0.2s ease-in;
        }
        #accordion .panel-heading .accordion-toggle.collapsed::before {
            color: #444;
            transform: rotate(-135deg);
            transition: all 0.2s ease-in;
        }

        #print-header {  display: none;}

        @media print {
            #print-header, #print-footer {display: block;}
        }

        .sl-nav {
  display: inline;
}
.sl-nav ul {
  margin:0;
  padding:0;
  list-style: none;
  position: relative;
  display: inline-block;
}
.sl-nav li {
  cursor: pointer;
  padding-bottom:10px;
}
.sl-nav li ul {
  display: none;
}
.sl-nav li:hover ul {
  position: absolute;
  top:29px;
  right:-15px;
  display: block;
  background: #fff;
  width: 120px;
  padding-top: 0px;
  z-index: 1;
  border-radius:5px;
  box-shadow: 0px 0px 20px rgba(0,0,0,0.2);
}
.sl-nav li:hover .triangle {
  position: absolute;
  top: 15px;
  right: -10px;
  z-index:10;
  height: 14px;
  overflow:hidden;
  width: 30px;
  background: transparent;
}
.sl-nav li:hover .triangle:after {
  content: '';
  display: block;
  z-index: 20;
  width: 15px;
  transform: rotate(45deg) translateY(0px) translatex(10px);
  height: 15px;
  background: #fff;
  border-radius:2px 0px 0px 0px;
  box-shadow: 0px 0px 20px rgba(0,0,0,0.2);
}
.sl-nav li ul li {
  position: relative;
  text-align: left;
  background: transparent;
  padding: 15px 15px;
  padding-bottom:0;
  z-index: 2;
  font-size: 15px;
  color: #3c3c3c;
}
.sl-nav li ul li:last-of-type {
  padding-bottom: 15px;
}
.sl-nav li ul li span {
  padding-left: 5px;
}
.sl-nav li ul li span:hover, .sl-nav li ul li span.active {
  color: #146c78;
}
.sl-flag {
  display: inline-block;
  box-shadow: 0px 0px 3px rgba(0,0,0,0.4);
  width: 15px;
  height: 15px;
  background: #aaa;
  border-radius: 50%;
  position: relative;
  top: 2px;
  overflow: hidden;
}
.flag-np {
  background: url('/images/np.png');  
  background-size: cover;
  background-position: center center;
}
.flag-usa {
  background-size: cover;
  background-position: center center;
  background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAZdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuMTM0A1t6AAABhUlEQVQ4T2Ows82PjGixsc4LD2tysC/09Kjw8622tyuICG8u0w/cpGSCBzF4e1VmZkzw9anOzOj38a4KCW4IC22ECHYk1l9tn4gHMeTlTnZxLikvm+XiUpKW2hvgX+vnV5OVOQEoOGfOtv94AYOzU3Fd7XxHh6Lq6rlurqUx0W0J8Z1AnbW18yotonaYuOJBDBXls4A+bGpaBCTz86YEBtQCvVBSPAPIbY0oP1/aiAcxABU1Ny+2tclvbFjo5FgUF9uenNwNDLnmpkWEnV1TPRcY1O1tS4H6i4umA/0MDK2K8tlAwRqHpP1uoXgQKKraWpcClTY3LQZaCLQ5NaUX5OaWJY3++SeTC/AgBmA4AXUClUJs9ver8fKsAAYEUJCws4G21dXNB1oFdD/Qz8DQTk4C+bm2dn6DZ9bRiDQ8iAEYt8CoBpK5YBIYw0AEEZwSXX4oMB4PYoC6gCzAcDqrjGzEsMfen2xEmbMv1rSTjRi26dqRjShz9o2+6WQjBrSShQSkZAIADvW/HLrLY6cAAAAASUVORK5CYII=');
}

    </style>
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
                    <a class="navbar-brand navbar-brand-small"><small class="text-primary">{{ \App\User::getAppRole() }}</small></a>
                    @endif
                    
            </div>
            <!-- /.navbar-header -->

            {{-- <ul class="nav navbar-nav navbar-top-links navbar-right"> --}}
            <div class="navbar-right">
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
                <ul class="nav navbar-nav" style="margin-top: -5px;">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>
                        @if(count(\App\Models\TransferWoman::healthpostTransferMessage())>0)
                        <span class="label label-danger label-info ">{{count(\App\Models\TransferWoman::healthpostTransferMessage())}}</span>
                        @endif 
                        <i class="fa fa-caret-down pull-right"></i> 
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        @if(count(\App\Models\TransferWoman::healthpostTransferMessage())>0)
                        @php
                            $transfers = \App\Models\TransferWoman::healthpostTransferMessage();
                        @endphp
                        @foreach($transfers as $transfer)
                        <li>
                            <a href="{{route('transfer-woman.transfer-confirm',[$transfer->from_hp_code, $transfer->woman_token])}}">
                                <div>
                                    <strong>{{\App\Models\Healthpost::getHealthpost($transfer->from_hp_code)}}</strong>
                                    <span class="pull-right text-muted">
                                        <em>{{$transfer->created_at->diffForHumans()}}</em>
                                    </span>
                                </div>
                                <div>{{$transfer->message}}</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        @endforeach
                        @else
                        <li>
                            <a href="#">
                                <div>No message available.</div>
                            </a>
                        </li>
                        @endif
                        
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
               </ul>
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            $('[id^="dataTables-example"]').DataTable({
                    responsive: true,
                    pageLength: 50,
                    "dom" : '<"top"f>rt<"button"lp><"clear">'
            });
        });

        document.querySelector("#print").addEventListener("click", function() {
        window.print();
        });

        function printDiv(printable) {
            var printContents = document.getElementById(printable).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        // Change the type of input to password or text 
        function TogglePassword() { 
            var temp = document.getElementById("password"); 
            if (temp.type === "password") { 
                temp.type = "text"; 
            } 
            else { 
                temp.type = "password"; 
            } 
        } 

        $(function () {
			  $('[data-toggle="tooltip"]').tooltip()
		});

    </script>
@yield('script')
</body>
</html>