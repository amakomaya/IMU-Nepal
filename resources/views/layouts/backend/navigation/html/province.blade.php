<li>
    <a href="#">
        {{ trans('sidebar.province') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('province.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('province.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>