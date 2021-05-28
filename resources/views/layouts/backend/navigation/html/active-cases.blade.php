<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>
        ACTIVE CASES <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        @can('cases-registration')
        <li>
            <a href="{{ route('woman.create') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Create
                <span class="label label-default pull-right" style="background-color: #0D3349"> Create </span>
            </a>
        </li>
        @endcan
        <li>
            <a href="{{ route('woman.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Registered Only
                <span class="label label-info pull-right" title="Registered Only"> RO </span>
            </a>
        </li>
            <li>
                <a href="{{ route('woman.pending-index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Pending
                    <span class="label label-default pull-right"> Pending </span>
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
