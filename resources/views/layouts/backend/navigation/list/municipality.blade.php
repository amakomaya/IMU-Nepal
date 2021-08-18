@include('layouts.backend.navigation.html.organization')
@include('layouts.backend.navigation.html.active-cases')
@include('layouts.backend.navigation.html.close-cases')
@include('layouts.backend.navigation.html.cases-other-organization')
@include('layouts.backend.navigation.html.cict-tracing')
@include('layouts.backend.navigation.html.cases-payment')
<li>
    <a href="{{ route('updateVaccinationCenter') }}">
        <i class="fa fa-hospital-o"></i>
         Update Vaccination center
    </a>
</li>


@include('layouts.backend.navigation.html.case-result-report')
@include('layouts.backend.navigation.html.monitoring')
