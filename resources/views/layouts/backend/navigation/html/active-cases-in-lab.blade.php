<li @if(Request::segment(2) == 'lab-patients-antigen' || Request::segment(2) == 'lab-patients' || 
    Request::segment(2) == 'lab-positive-patients-antigen' || Request::segment(2) == 'lab-positive-patients' || 
    Request::segment(2) == 'lab-negative-patients-antigen' || Request::segment(2) == 'lab-negative-patients'
    ) class="active" 
    @endif>

    <a href="#">
        <i class="fa fa-user" aria-hidden="true"></i>
        ACTIVE CASES IN LAB<span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="#">
                <i class="fa fa-flask" aria-hidden="true" style="color: yellow;"></i> 
                Lab Received <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('lab.patient.antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('lab.patient.index') }}">
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
                    <a href="{{ route('lab.positive.patients.antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('lab.positive.patients.index') }}">
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
                    <a href="{{ route('lab.negative.patients.antigen.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Antigen
                        <span class="label label-success pull-right" title="Rapid Antigen Test"> RAT </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('lab.negative.patients.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR
                        <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> PCR </span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>