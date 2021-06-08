<li>
    <a href="#">
        <i class="fa fa-building-o" aria-hidden="true"></i>
        Related Offices <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('municipality.index') }}">
                Municipality
            </a>
        </li>
        <li>
            <a href="{{ route('healthpost.index') }}">
                Organizations
            </a>
        </li>
    </ul>
</li>
@include('layouts.backend.navigation.html.active-cases')
@include('layouts.backend.navigation.html.close-cases')

@include('layouts.backend.navigation.html.cases-payment')
@include('layouts.backend.navigation.html.map')
