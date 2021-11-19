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
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || auth()->user()->role == 'province')
        <li>
            <a href="{{ route('healthpost.create') }}">Create</a>
        </li>
        @endif
        @if(auth()->user()->role == 'municipality')
        <li>
            <a href="{{ route('healthpost.index') }}">
                <i class="fa fa-building-o"></i>
                Hospitals / CICT Teams
            </a>
            <a href="{{ route('admin.vaccination-center') }}">
                <i class="fa fa-hospital-o"></i>
                Vaccination Center
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
            <a href="{{ route('organization.overview.labtest') }}" title="PCR Lab Test Only">PCR Lab Test Only</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.both') }}" title="PCR Lab & Treatment( Hospital )">PCR L&T( Hospital )</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.hospitalnopcr') }}" title="Hospital without PCR Lab">Hospital without PCR Lab</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.antigenonly') }}" title="Antigen Test Only">Antigen Test Only</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.poe') }}" title="Point of Entry (POE)">POE</a>
        </li>
        <li>
            <a href="{{ route('organization.overview.vaccination') }}" title="Vaccination Center">Vaccination Center</a>
        </li>
        @endif
    </ul>
</li>
