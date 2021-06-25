@include('layouts.backend.navigation.html.dashboard')

@canany(['cases-registration','poe-registration'])
    @include('layouts.backend.navigation.html.active-cases')
    @include('layouts.backend.navigation.html.close-cases')
@endcan

@can('lab-received')
    @include('layouts.backend.navigation.html.active-cases-in-lab')
@endcan
    
@can('cases-registration','lab-received')
    @include('layouts.backend.navigation.html.case-result-report')
@endcan

@can('cases-payment')
    @include('layouts.backend.navigation.html.cases-payment')
@endcan

@include('layouts.backend.navigation.html.bulk-upload')

@include('layouts.backend.navigation.html.permission-list')

