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
<li>
    <a href="#">
    {{ trans('sidebar.dho') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('dho.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('dho.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('health-worker.index') }}">
    <i class="fa fa-user"></i>
        Health Worker
    </a>
</li>
<li>
    <a href="{{ route('woman.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        Patients
    </a>
</li>
<li>
    <a href="{{ route('center.woman.map') }}">
    <i class="fa fa-map-marker"></i>
            Map 
    </a>
</li>

 <li>
    <a href="{{ route('admin.overview') }}">
            <i class="fa fa-database" aria-hidden="true"></i> {{ trans('sidebar.overview_of_data') }}
    </a>
</li>

<!-- <li>
    <a href="{{ route('backup-restore.index') }}">
        <i class="fa fa-undo" aria-hidden="true"></i> {{ trans('sidebar.backup_restore') }}
    </a>
</li> -->