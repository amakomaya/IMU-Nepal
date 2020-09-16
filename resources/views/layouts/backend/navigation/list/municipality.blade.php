@if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Municipality')
<li>
    <a href="#">
    <i class="fa fa-building-o" aria-hidden="true"></i>
        {{ trans('sidebar.ward') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('ward.index') }}">{{trans('sidebar.list')}}</a>
        </li>
        <li>
            <a href="{{ route('ward.create') }}">{{trans('sidebar.create')}}</a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
    <i class="fa fa-building-o" aria-hidden="true"></i>
    {{trans('sidebar.health_post')}} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('healthpost.index') }}">{{trans('sidebar.list')}}</a>
        </li>
        <li>
            <a href="{{ route('healthpost.create') }}">{{trans('sidebar.create')}}</a>
        </li>
    </ul>
</li>
@else
<li>
    <a href="{{ route('ward.index') }}">
    <i class="fa fa-building-o"></i>
    {{trans('sidebar.ward')}}
    </a>
</li>
<li>
    <a href="{{ route('healthpost.index') }}">
    <i class="fa fa-building-o"></i>
    {{trans('sidebar.health_post')}}
    </a>
</li>
@endif
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
<!-- <li>
    <a href="{{ route('backup-restore.index') }}">
        <i class="fa fa-undo" aria-hidden="true"></i> {{trans('sidebar.backup_restore')}} 
    </a>
</li> -->