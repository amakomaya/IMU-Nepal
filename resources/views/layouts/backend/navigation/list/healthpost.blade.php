@if($h_type != 4)
    <li>
        <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>
            Users <span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('health-worker.index') }}">{{ trans('sidebar.list') }}</a>
            </li>
            @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Province')
                <li>
                    <a href="{{ route('health-worker.create') }}">{{ trans('sidebar.create') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if($h_type !== 4)
    @include('layouts.backend.navigation.html.active-cases')
    @include('layouts.backend.navigation.html.close-cases')
@endif
@if($h_type == 2 || $h_type == 3)
    @include('layouts.backend.navigation.html.active-cases-in-lab')
@endif
@if($h_type == 1 || $h_type == 3 || $h_type == 5 || $h_type == 6)
    @include('layouts.backend.navigation.html.cases-payment')
@endcan