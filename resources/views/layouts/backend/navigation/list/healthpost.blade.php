<li>
    <a href="#">
    <i class="fa fa-user" aria-hidden="true"></i>
    {{ trans('sidebar.health_worker') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('health-worker.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('health-worker.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
    <i class="fa fa-user" aria-hidden="true"></i>

    Lab Users <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('fchv.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('fchv.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>
<li>
    <a href="{{ route('woman.index') }}">
    <i class="fa fa-users" aria-hidden="true"></i>
    COVID-19 Cases
    </a>
</li>

<li>
    <a href="{{ route('woman.map') }}">
    <i class="fa fa-globe" aria-hidden="true"></i>
        Map
    </a>
</li>