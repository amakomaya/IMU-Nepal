@if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main' || \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Municipality')
    <li>
        <a href="#">
            <i class="fa fa-building-o" aria-hidden="true"></i>
            Organizations <span class="fa arrow"></span>
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
@php($token =Auth::user()->token )
<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>

        ACTIVE CASES <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('woman.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Registered, Pending
                <span class="label label-info pull-right"> R.P. </span>
            </a>
        </li>
        <li>
            <a href="{{ route('patients.lab-received.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Lab Received
                <span class="label label-warning pull-right"> Lab Received </span>
            </a>
        </li>
        <li>
            <a href="{{ route('patients.positive.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Positive
                <span class="label label-danger pull-right">Positive</span>

            </a>
        </li>
        <li>
            <a href="{{ route('patients.negative.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Negative
                <span class="label label-success pull-right">Negative</span>

            </a>
        </li>
        <li>
            <a href="{{ route('patients.tracing.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Contact Tracing
                <span class="label label-primary pull-right">Tracing</span>

            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>

        CLOSED CASES <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('cases.recovered.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Recovered / Discharged
                <span class="label label-success pull-right">R/D</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cases.death.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Deaths
                <span class="label label-warning pull-right"> Deaths </span>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="{{ route('patients.other-organization.index') }}" title="Cases in Other Organization">
        <i class="fa fa-users"></i>
        Cases in Other Orgs.
        <span class="label label-default pull-right"> OC </span>
    </a>
</li>
<li>
    <a href="{{ route('vaccination.report') }}">
        <i class="fa fa-dashboard" aria-hidden="true"></i>
        Vaccination Reports
    </a>
</li>
<li>
    <a href="{{ route('dho.vaccination.municipalities') }}">
        <i class="fa fa-hospital-o"></i>
        Vaccination Center
    </a>
</li>

<li>
    <a href="{{ route('updateVaccinationCenter') }}">
        <i class="fa fa-hospital-o"></i>
         Update Vaccination center
    </a>
</li>

<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>
        Health Professional <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('health.professional.add') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Create
                <span class="label label-info pull-right"><i class="fa fa-plus"></i></span>
            </a>
        </li>
        <li>
            <a href="{{ route('health-professional.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Registered
                <span class="label label-primary pull-right">&#x2714;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('health-professional.immunized') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Immunized
                <span class="label label-success pull-right">&#x2714;</span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('lab.patient.report.index') }}">
        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
        Case Report
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