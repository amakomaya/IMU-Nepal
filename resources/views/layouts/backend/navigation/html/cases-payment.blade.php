<li @if(Request::segment(3) == 'monthly-line-listing' || Request::segment(3) == 'overview' || 
    Request::segment(3) == 'daily-listing'|| Request::segment(3) == 'situation-report'|| 
    Request::segment(2) == 'cases-patient-detail' || Request::segment(2) == 'cases-report-payment') class="active" @endif>
    <a href="#">
        <i class="fa fa-money" aria-hidden="true" title="Covid Designated Institution"></i>
        Designated Covid OU <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        @if(auth()->user()->role === 'main' || auth()->user()->role === 'center' || auth()->user()->role === 'province')
        <li>
            <a href="{{ route('public.home.index') }}" target="_blank">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                Public Portal
                <span class="label label-info pull-right">Portal</span>
            </a>
        </li>
        @endif
        @if(auth()->user()->role === 'healthpost' || auth()->user()->hasDirectPermission('cases-payment'))
            <li>
                <a href="{{ route('cases.payment.create') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Create
                    <span class="label label-default pull-right"> Create </span>
                </a>
            </li>
        @endif
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

        @if(auth()->user()->role == 'healthpost' || auth()->user()->role == 'healthworker')
        <li>
            <a href="{{ route('cases.payment.report') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                DHIS2 Report
                <span class="label label-info pull-right">HMIS</span>
            </a>
        </li>
        @else
        <li @if(Request::segment(3) == 'cases-report-payment') @endif>
            <a href="#">
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                DHIS2 Reports <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('cases.payment.report', ['type' => '1']) }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        PCR Lab & Treatment and Hospital without PCR Lab
                    </a>
                </li>
                <li>
                    <a href="{{ route('cases.payment.report', ['type' => '2']) }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Home Isolation
                    </a>
                </li>
                <li>
                    <a href="{{ route('cases.payment.report', ['type' => '3']) }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Institutional Isolation
                    </a>
                </li>
                <li>
                    <a href="{{ route('cases.payment.report') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Overall
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li @if(Request::segment(3) == 'overview' || Request::segment(3) == 'daily-listing' || Request::segment(3) == 'monthly-line-listing') class="active" @endif >
            <a href="#">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                Reports <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                @if(auth()->user()->role == 'main')
                    <li>
                        <a href="#">
                            <i class="fa fa-calculator"></i>
                            Cost Calculation <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-forth-level">
                            <li>
                                <a href="{{ route('report.case-payment-overview') }}">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                    Overview
                                    <span class="label label-primary pull-right"> Monthly </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('report.case-payment-daily-listing') }}">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                    Daily List
                                    <span class="label label-primary pull-right"> Daily </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="{{ route('report.case-payment-monthly-line-listing') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Line Listing
                        <span class="label label-info pull-right"> Monthly </span>
                    </a>
                </li>
                @if(auth()->user()->role == 'main' || auth()->user()->role == 'province' || 
                auth()->user()->role == 'dho' || auth()->user()->role == 'municipality')
                <li>
                    <a href="{{ route('report.case-payment-situation-report') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Situation Report
                        <span class="label label-info pull-right"> SR </span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('cases.patient.detail') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Patient Detail
                        <span class="label label-primary pull-right">Detail</span>
                    </a>
                </li>
            </ul>
        </li>
        @if(auth()->user()->role === 'main' || auth()->user()->role === 'province' || auth()->user()->role === 'municipality' || auth()->user()->role === 'dho')
        <li>
            <a href="#">
                <i class="fa fa-money" aria-hidden="true"></i>
                By Organization <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{ route('cases.payment.by.organization') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        All
                        <span class="label label-info pull-right">All</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cases.payment.by.institutional') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Institutional
                        <span class="label label-info pull-right">Isolation</span>
                    </a>
                </li>
            <li>
                <a href="{{ route('cases.payment.by.lab-treatment') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Lab & Treatment
                    <span class="label label-info pull-right">LNT</span>
                </a>
            </li>
            <li>
<a href="{{ route('cases.payment.by.hospital.wo.pcrlab') }}">
    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
    Hospital without PCR Lab
    <span class="label label-info pull-right">HPCR</span>
</a>
</li>

</ul>
</li>
@endif
@if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Province' || auth()->user()->role === 'province' || auth()->user()->role === 'dho' || auth()->user()->role === 'municipality' || auth()->user()->role === 'main' || auth()->user()->role === 'healthpost')
<li>
<a href="#">
<i class="fa fa-medkit" aria-hidden="true"></i>
Medicine Stocks <span class="fa arrow"></span>
</a>
<ul class="nav nav-third-level">
@if(auth()->user()->role === 'healthpost')
    <li>
        <a href="{{ route('stock.updateList') }}">
            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
            Create
            <span class="label label-default pull-right">Create</span>
        </a>
    </li>
        <li>
            <a href="{{ route('stock.transaction.list') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                History
                <span class="label label-default pull-right">History</span>
            </a>
        </li>
@endif
    @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Province' || auth()->user()->role === 'province' || auth()->user()->role === 'dho' || auth()->user()->role === 'municipality' || auth()->user()->role === 'main')
        <li>
            <a href="{{ route('stock.list') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                List
                <span class="label label-primary pull-right"> List </span>
            </a>
        </li>
    @endif
</ul>
</li>
@endif
<li>
<a href="#">
<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
Observation Cases <span class="fa arrow"></span>
</a>
<ul class="nav nav-third-level">
@if(auth()->user()->role === 'healthpost')
<li>
    <a href="{{ route('observation-cases.create') }}">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
        Create
        <span class="label label-default pull-right">Create</span>
    </a>
</li>
@endif
<li>
<a href="{{ route('observation-cases.index') }}">
    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
    List
    <span class="label label-primary pull-right"> List </span>
</a>
</li>
</ul>
</li>
</ul>
</li>

