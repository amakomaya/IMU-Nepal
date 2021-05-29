<li>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>
        
        ACTIVE CASES <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        @can('cases-registration')
            <li>
                <a href="{{ route('woman.create') }}">
                    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                    Create
                    <span class="label label-default pull-right" style="background-color: #0D3349"> Create </span>
                </a>
            </li>
        @endcan
        <li>
            <a href="{{ route('woman.index') }}">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                Registered Only
                <span class="label label-info pull-right" title="Registered Only"> RO </span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-spinner" aria-hidden="true"></i> 
                Pending <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('woman.antigen-pending-index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-default pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('woman.pending-index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-default pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-flask" aria-hidden="true"></i> 
                Lab Received <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.lab-received-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-default pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.lab-received.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-default pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 
                Positive <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.positive-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-default pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.positive.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-default pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-check" aria-hidden="true"></i> 
                Negative <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.negative-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-default pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.negative.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-default pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
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
