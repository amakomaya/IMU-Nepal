@include('layouts.backend.navigation.html.organization')
@include('layouts.backend.navigation.html.active-cases')
@include('layouts.backend.navigation.html.close-cases')
@include('layouts.backend.navigation.html.cases-other-organization')
@include('layouts.backend.navigation.html.cases-payment')
@include('layouts.backend.navigation.html.swab')
<li>
    <a href="{{ route('updateVaccinationCenter') }}">
        <i class="fa fa-hospital-o"></i>
         Update Vaccination center
    </a>
</li>
@include('layouts.backend.navigation.html.case-report')
@include('layouts.backend.navigation.html.map')