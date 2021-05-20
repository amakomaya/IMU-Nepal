<li>
    <a href="#">
        <i class="fa fa-user" aria-hidden="true"></i>
        Notice Board <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('notice-board.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('notice-board.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>
