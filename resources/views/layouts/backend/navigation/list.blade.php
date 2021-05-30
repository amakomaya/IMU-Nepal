<ul class="nav" id="side-menu">
    
    <!-- Authentication Links -->
    
    @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
    @else

        @if(Auth::user()->role=="main")
            @include ('layouts.backend.navigation.list.main')
        @endif

        @if(Auth::user()->role=="center")
            @include ('layouts.backend.navigation.list.center')
        @endif

        @if(Auth::user()->role=="province")
            @include ('layouts.backend.navigation.list.province')
        @endif

        @if(Auth::user()->role=="dho")
            @include ('layouts.backend.navigation.list.dho')
        @endif

        @if(Auth::user()->role=="municipality")
            @include ('layouts.backend.navigation.list.municipality')
        @endif

        @if(Auth::user()->role=="healthpost")
            @include ('layouts.backend.navigation.list.healthpost')
        @endif

        @if(Auth::user()->role=="healthworker")
            @include ('layouts.backend.navigation.list.healthworker')
        @endif
        
        @if(Request::session()->get('user_show')===true)
        <li>
            <a href="{{ route('user-manager.first-loggedin') }}"
                onclick="event.preventDefault();
                         document.getElementById('login-form').submit();">
                    {{ __('sidebar.go_to_admin', ['name' => \App\User::getFirstLoggedInRole(Request::session()->get('user_token'))]) }} 

            </a>

            <form id="login-form" action="{{ route('user-manager.first-loggedin') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        @endif
        @if(Request::session()->get('user_token') === '5a4425')
                <li>
                    <a href="{{ route('backup-restore.index') }}">
                        <i class="fa fa-undo" aria-hidden="true"></i> {{ trans('sidebar.backup_restore') }}
                    </a>
                </li>
            @endif
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
        @include('layouts.backend.navigation.html.forum')

    @endguest
</ul>

                    