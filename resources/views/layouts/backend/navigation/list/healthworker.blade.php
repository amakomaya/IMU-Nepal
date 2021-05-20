@can('cases-registration')
    @include('layouts.backend.navigation.html.active-cases')
    @include('layouts.backend.navigation.html.close-cases')
    @include('layouts.backend.navigation.html.case-report')
@endcan

@can('lab-received')
    @include('layouts.backend.navigation.html.active-cases-in-lab')
    @include('layouts.backend.navigation.html.case-report')

@endcan

@can('cases-payment')
    @include('layouts.backend.navigation.html.cases-payment')
@endcan


