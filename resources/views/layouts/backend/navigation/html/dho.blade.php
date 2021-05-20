<li>
    <a href="#">
        {{ trans('sidebar.dho') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('dho.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
            <li>
                <a href="{{ route('dho.create') }}">{{ trans('sidebar.create') }}</a>
            </li>
        @endif
    </ul>
</li>