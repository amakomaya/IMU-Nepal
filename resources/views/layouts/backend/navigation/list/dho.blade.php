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
                Hospitals / CICT Teams
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="{{ route('health-worker.index') }}">
        <i class="fa fa-user"></i>
        Health Worker
    </a>
</li>
<li>
    <a href="#">
        <i class="fa fa-user" aria-hidden="true"></i>

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
<li>
    <a href="{{ route('dho.vaccination.municipalities') }}">
        <i class="fa fa-hospital-o"></i>
        Vaccination Center
    </a>
</li>
<li>
    <a href="{{ route('center.woman.map') }}">
        <i class="fa fa-map-marker"></i>
        Map
    </a>
</li>