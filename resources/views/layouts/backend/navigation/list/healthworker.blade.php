@can('cases-registration')
    <li>
        <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>
            ACTIVE CASES <span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('patient.multiple-sample.create') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Add Sample Collection
                    <span class="label label-default pull-right"> + </span>
                </a>
            </li>
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
        <a href="{{ route('lab.patient.report.index') }}">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            Case Report
        </a>
    </li>
@endcan

@can('lab-received')
    <li>
        <a href="#">
            <i class="fa fa-users" aria-hidden="true"></i>

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
    <li>
        <a href="{{ route('lab.patient.report.index') }}">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            Case Report
        </a>
    </li>
@endcan

@can('vaccination')
    <li>
        <a href="#">
            <i class="fa fa-users" aria-hidden="true"></i>

            Vaccination Records<span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('vaccination.web.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    List
                    <span class="label label-info pull-right">List </span>
                </a>
            </li>
            <li>
                <a href="{{ route('vaccinated.web.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Vaccinated
                    <span class="label label-success pull-right">&#x2714;</span>

                </a>
            </li>
        </ul>
    </li>
@endcan
@can('cases-payment')

<li>
    <a href="#">
        <i class="fa fa-money" aria-hidden="true"></i>

        CASES Payment <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('cases.payment.create') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Create
                <span class="label label-default pull-right"> Create </span>
            </a>
        </li>
        <li>
            <a href="{{ route('cases.payment.report') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Report
                <span class="label label-info pull-right">Report</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cases.payment.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Under Treatment
                <span class="label label-primary pull-right">List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cases.payment.index-discharge') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Discharge
                <span class="label label-success pull-right">List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cases.payment.index-death') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Death
                <span class="label label-danger pull-right">List</span>
            </a>
        </li>
    </ul>
</li>
@endcan


