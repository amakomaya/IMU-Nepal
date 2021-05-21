<li>
    <a href="#">
        <i class="fa fa-hospital-o" aria-hidden="true"></i> Organizations <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        @if(auth()->user()->role == 'main' || auth()->user()->role == 'center' || auth()->user()->role == 'dho' || auth()->user()->role == 'province')
        <li>
            <a href="{{ route('organization.overview.search') }}" title="Search and Edit Organizations">Search</a>
        </li>
        @endif
        @if(auth()->user()->role == 'municipality')
            <li>
                <a href="{{ route('healthpost.index') }}">List</a>
            </li>
            @endif
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || auth()->user()->role == 'province')
        <li>
            <a href="{{ route('healthpost.create') }}">Create</a>
        </li>
        @else
            <li>
                <a href="{{ route('healthpost.index') }}">
                    <i class="fa fa-building-o"></i>
                    Hospitals / CICT Teams
                </a>
            </li>
        @endif
        @if(auth()->user()->role == 'main' || auth()->user()->role == 'center' || auth()->user()->role == 'dho' || auth()->user()->role == 'province')
        <li>
            <a href="{{ route('organization.overview.cict') }}" title="HOME Isolation">HOME Isolation</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.hospital') }}" title="Institutional Isolation">Institutional Isolation</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.labtest') }}" title="Lab Test Only">Lab Test Only</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.both') }}" title="Lab & Treatment">Lab & Treatment</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.normal') }}" title="Normal">Normal</a>
        </li>
        @endif
    </ul>
</li>
