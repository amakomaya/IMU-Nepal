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
@include('layouts.backend.navigation.html.cict-tracing')
@can('cases-payment')
    @include('layouts.backend.navigation.html.cases-payment')
@endcan

<?php
 $userToken = auth()->user()->token;
 $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
 $hpCode = $healthWorker->hp_code;
 $organizationType = \App\Models\Organization::where('hp_code', $hpCode)->first()->hospital_type;
  if($organizationType == 1) {
?>
  @include('layouts.backend.navigation.html.community-deaths')
<?php
  }
?>

@include('layouts.backend.navigation.html.bulk-upload')

@include('layouts.backend.navigation.html.permission-list')

