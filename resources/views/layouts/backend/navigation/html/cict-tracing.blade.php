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
        <li>
            <a href="{{ route('cict-tracing.index') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                List
                <span class="label label-danger pull-right">List</span>

            </a>
        </li>
        @endif
        @if(auth()->user()->role == 'province')
        <li>
            <a href="{{ route('cict-tracing.province-report') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Report
                <span class="label label-danger pull-right">Report</span>

            </a>
        </li>
        @endif
        @if(auth()->user()->role == 'dho')
        <li>
            <a href="{{ route('cict-tracing.district-report') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Report
                <span class="label label-danger pull-right">Report</span>

            </a>
        </li>
        @endif
    </ul>
</li>