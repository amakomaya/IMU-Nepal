<li @if(Request::segment(2) == 'patients' || 
    Request::segment(2) == 'antigen-patients-pending' || Request::segment(2) == 'antigen-patients-pending-old' || 
    Request::segment(2) == 'lab-received-patients-antigen' || Request::segment(2) == 'lab-received-patients-antigen-old' || 
    Request::segment(2) == 'lab-received-patients' || Request::segment(2) == 'lab-received-patients-old' ||
    Request::segment(2) == 'positive-patients-antigen' || Request::segment(2) == 'positive-patients-antigen-old' || 
    Request::segment(2) == 'positive-patients' || Request::segment(2) == 'positive-patients-old' ||
    Request::segment(2) == 'negative-patients-antigen' || Request::segment(2) == 'negative-patients-antigen-old' ||
    Request::segment(2) == 'negative-patients' || Request::segment(2) == 'negative-patients-old' ||
    Request::segment(2) == 'situation-report')
    class="active" @endif>
    <a href="#">
        <i class="fa fa-users" aria-hidden="true"></i>
        
        CASES OVERVIEW<span class="fa arrow"></span>
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
                <i class="fa fa-spinner" aria-hidden="true" style="color: black;"></i> 
                Pending <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('woman.antigen-pending-index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('woman.pending-index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-flask" aria-hidden="true" style="color: yellow;"></i> 
                Lab Received <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.lab-received-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.lab-received.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: red;"></i> 
                Positive <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.positive-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.positive.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-check" aria-hidden="true" style="color: green;"></i> 
                Negative <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('patients.negative-antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.negative.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('patients.tracing.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true" style="color: blue;"></i>
                Contact Tracing
                <span class="label label-primary pull-right">Tracing</span>

            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-file-pdf-o" aria-hidden="true" style="color: green;"></i> 
                Situation Reports <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('report.sample-report.ancs') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Organization
                        <span class="label label-success pull-right" title="Organization"> Org </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('report.sample-report.lab') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Lab
                        <span class="label label-info pull-right" title="Lab"> Lab </span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>
