<li>
    <a href="#">
        <i class="fa fa-user" aria-hidden="true"></i>
        Contact Tracing<span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        @if(auth()->user()->role == 'healthworker')
        <li>
            <a href="{{ route('cict-tracing.search') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Create
                <span class="label label-warning pull-right"> Create </span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('cict-tracing.index') }}">
                <i class="fa fa-list" aria-hidden="true"></i>
                List
                <span class="label label-primary pull-right">List</span>
                
            </a>
        </li>
        <li>
            <a href="{{ route('cict-tracing-transferred') }}">
                <i class="fa fa-arrows-h" aria-hidden="true" style="color: blue;"></i>
                Transferred Cases
            </a>
        </li>
        @if(auth()->user()->role == 'province')
        <ul class="nav nav-second-level">
            <li>
                <a href="#">
                    <i class="fa fa-file" aria-hidden="true" style="color: blue;"></i> 
                    Report <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-third-level">
                    <li>
                        <a href="{{ route('cict-tracing.province-districtwise-report') }}">
                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                            District
                            <span class="label label-success pull-right" title="District"> District </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cict-tracing.province-municipalitywise-report') }}">
                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                            LLG
                            <span class="label label-warning pull-right" title="Polymerase Chain Reaction"> LLG </span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        @endif
        @if(auth()->user()->role == 'dho')
        <li>
            <a href="{{ route('cict-tracing.district-municipalitywise-report') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Report
                <span class="label label-danger pull-right">Report</span>

            </a>
        </li>
        @endif
    </ul>
</li>