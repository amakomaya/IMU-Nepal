<li>
    <a href="{{ route('center.index') }}">
       {{ trans('sidebar.center') }} <span class=""></span>
    </a>
</li>

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
    <a href="#">
    {{ trans('sidebar.local_level') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('municipality.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        <li>
            <a href="{{ route('municipality.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
    </ul>
</li>
 <li>
    <a href="{{ route('admin.overview') }}">
            <i class="fa fa-hospital-o" aria-hidden="true"></i> Hospitals / CICT Teams
    </a>
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
        <span class="label label-info pull-right">Active</span>

    </a>
</li>
<li>
    <a href="{{ route('patients.negative.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        COVID-19 Cases
        <span class="label label-success pull-right">Negative</span>

    </a>
</li>

<!-- <li>
    <a href="{{ route('backup-restore.index') }}">
        <i class="fa fa-undo" aria-hidden="true"></i> {{ trans('sidebar.backup_restore') }}
    </a>
</li> -->

<li>
    <a href="{{ route('activity-log.index') }}">
        <i class="fa fa-history" aria-hidden="true"></i> {{ trans('sidebar.activity_log') }}
    </a>
</li>