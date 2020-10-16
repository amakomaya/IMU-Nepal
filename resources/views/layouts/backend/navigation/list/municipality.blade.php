@if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Municipality')
<li>
    <a href="#">
    <i class="fa fa-building-o" aria-hidden="true"></i>
        Hospitals / CICT Teams <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('healthpost.index') }}">{{trans('sidebar.list')}}</a>
        </li>
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
        <li>
            <a href="{{ route('healthpost.create') }}">{{trans('sidebar.create')}}</a>
        </li>
        @endif
    </ul>
</li>
@else
<li>
    <a href="{{ route('healthpost.index') }}">
    <i class="fa fa-building-o"></i>
        Hospitals / CICT Teams
    </a>
</li>
@endif
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