<li>
    <a href="#">
    <i class="fa fa-user" aria-hidden="true"></i>
    Users <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('health-worker.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
        <li>
            <a href="{{ route('health-worker.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
        @endif
    </ul>
</li>
@if($h_type == 1 || $h_type == 3)
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
@endif
@if($h_type == 2 || $h_type == 3)
    <li>
        <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>

            ACTIVE CASES IN LAB<span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('lab.patient.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Lab Received
                    <span class="label label-warning pull-right"> Lab Received </span>
                </a>
            </li>
            <li>
                <a href="{{ route('lab.positive.patients.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Positive
                    <span class="label label-danger pull-right">Positive</span>

                </a>
            </li>
            <li>
                <a href="{{ route('lab.negative.patients.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Negative
                    <span class="label label-success pull-right">Negative</span>

                </a>
            </li>
        </ul>
    </li>
@endif
<li>
    <a href="{{ route('vaccination.report') }}">
        <i class="fa fa-dashboard" aria-hidden="true"></i>
        Vaccination Reports
    </a>
</li>
<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>
        Health Professional <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('health-professional.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Registered
                <span class="label label-info pull-right">R</span>
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
    <i class="fa fa-globe" aria-hidden="true"></i>
        Map
    </a>
</li> 