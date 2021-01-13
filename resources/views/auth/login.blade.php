<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}"/>

  <title>{{ config('app.name') }}</title>

    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <style>
            
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

  .input-group-addon .fa {
    font-size: 18px;
  }
  </style>
</head>
<body style="background-color: #F7F7F7;">
    <br>
    <div class="container">
        <div class="sl-nav pull-right">
            @lang('app.language') :
            <ul>
            <li><b>{{ (app()->getLocale() == 'np') ? 'नेपाली' : 'English' }}</b> <i class="fa fa-angle-down" aria-hidden="true"></i>
                <div class="triangle"></div>
                <ul>
                  <li><a href="{{ url('locale/en') }}"><i class="sl-flag flag-usa"></i> <span class="active">English</span></a></li>
                <li><a href="{{ url('locale/np') }}"><i class="sl-flag flag-np"></i> <span>नेपाली</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
    <div class="rows">
        <div style="display:inline-block; width:100%; height:auto;">

            <img class="img-responsive center-block" src="{{ asset('images/login-icon.png') }}">
        </div>
        @if (Request::session()->has('forget_password_message'))
            <div class="alert alert-block alert-success" style="margin:15px; text-align:center;">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <p>
                    <strong>Thank you !</strong><br>
                    If the username, email or phone you entered exists in our system. We will provide you new password through email or your phone number.
                </p>

            </div>
        @endif
    </div>
        <div class="row">
    <div class="col-md-4">
        <div class="panel panel-default" style="margin-top:30px">
            <h3 class="text-center"> {{ config('app.name') }} Login</h3>
            @if (Request::session()->has('error_message'))
                <div class="alert alert-block alert-danger" style="margin:15px; text-align:center;">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {!! Request::session()->get('error_message') !!}

                </div>
            @endif
            <div class="panel-body">
                <form role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <span class="input-group-addon" title="@lang('login.username')"><i class="fa fa-user"></i></span>
                                <input class="form-control" placeholder="@lang('login.username')" id="username" name="username" value="{{ old('username') }}" type="text" autofocus>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <span class="input-group-addon" title="@lang('login.password')"><i class="fa fa-lock"></i></span>
                                <input class="form-control" id="password" placeholder="@lang('login.password')" name="password" type="password" value="">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <input type="checkbox" onclick="TogglePassword()">
                            <b>@lang('login.show_password')</b>
                        </div>
                        <input type="submit" class="btn btn-md btn-primary btn-block" value="@lang('login.login')">
                    <br>
                    <div class="clearfix">
                        <a href="#" class="pull-right" data-toggle="modal" data-target="#forgetPasswordModel">Forgot Password ?</a>
                    </div>
                </form>
            </div>

        </div>
        <div style="display:inline-block; width:100%; height:auto;">

{{--            <a href="{{ route('health.professional.add') }}" class="btn btn-info center-block" role="button"><i class="fa fa-plus" aria-hidden="true"></i>--}}
{{--                Add Health Professional Data</a>--}}

            <a target="_blank" href="https://play.google.com/store/apps/details?id=com.aamakomaya.hamrosurvey"><img class="img-responsive center-block" src="{{ asset('images/google-play-badge.png') }}"></a>
        </div>
    </div>

    <div class="col-md-4" style="margin-top: 30px";>
    @foreach(App\Models\NoticeBoard::latest()->get() as $row)
            @if($row->type == 'Warning')
                <div class="panel panel-warning">
                    @elseif($row->type == 'Danger')
                        <div class="panel panel-danger">
                            @else
                                <div class="panel panel-info">
                                    @endif
            <div class="panel-heading"><h4>{{ $row->title }}</h4></div>
            <div class="panel-body">
                {!! $row->description !!}
                <span class="badge">Posted {{ $row->created_at->diffForHumans() }}</span>

            </div>
        </div>
        @endforeach
    </div>
                        <div class="col-md-4" style="margin-top: 30px";>

                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/videoseries?list=PLDauIRTtxpwjUbaKjZuPX5l9W4BwsUvGu" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                </div>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">सूचना ! सूचना!! सूचना!!!</h4> <strong class="pull-right">१७ पुष २०७७, शुक्रवार</strong>
                            </div>
                            <div class="modal-body">
                                <img src="{{ asset('images/COVID-health_professional-letter.jpg') }}" alt="" class="img-responsive">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                @include('auth.passwords.reset')

                <!-- Load Facebook SDK for JavaScript -->
                <div id="fb-root"></div>
                <script>
                    window.fbAsyncInit = function() {
                        FB.init({
                            xfbml            : true,
                            version          : 'v8.0'
                        });
                    };

                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>

                <!-- Your Chat Plugin code -->
                <div class="fb-customerchat"
                     attribution=setup_tool
                     page_id="100681058478904">
                </div>
                <script>
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

    </script>
                </div>

    </div>
        <script type="text/javascript">
            $(window).on('load',function(){
                $('#myModal').modal('show');
            });
        </script>
</body>
</html>