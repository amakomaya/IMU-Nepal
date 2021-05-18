<li>
    <a href="#">
    {{ trans('sidebar.dho') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('dho.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
        <li>
            <a href="{{ route('dho.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
        @endif
    </ul>
</li>
<li>
    <a href="#">
    {{ trans('sidebar.local_level') }} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('municipality.index') }}">{{ trans('sidebar.list') }}</a>
        </li>
        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
        <li>
            <a href="{{ route('municipality.create') }}">{{ trans('sidebar.create') }}</a>
        </li>
        @endif
    </ul>
</li>
 <li>
    <a href="{{ route('admin.overview') }}">
            <i class="fa fa-hospital-o" aria-hidden="true"></i> Organizations
    </a>
</li>
{{--<li>--}}
{{--    <a href="#">--}}
{{--    <i class="fa fa-user" aria-hidden="true"></i>--}}

{{--    Lab Users <span class="fa arrow"></span>--}}
{{--    </a>--}}

{{--    <ul class="nav nav-second-level">--}}
{{--        <li>--}}
{{--            <a href="{{ route('fchv.index') }}">{{ trans('sidebar.list') }}</a>--}}
{{--        </li>--}}
{{--        @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')--}}
{{--        <li>--}}
{{--            <a href="{{ route('fchv.create') }}">{{ trans('sidebar.create') }}</a>--}}
{{--        </li>--}}
{{--        @endif--}}
{{--    </ul>--}}
{{--</li>--}}
{{--<li>--}}
{{--    <a href="#">--}}
{{--        <i class="fa fa-user" aria-hidden="true"></i>--}}

{{--        ACTIVE CASES <span class="fa arrow"></span>--}}
{{--    </a>--}}

{{--    <ul class="nav nav-second-level">--}}
{{--        <li>--}}
{{--            <a href="{{ route('woman.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Registered, Pending--}}
{{--                <span class="label label-info pull-right"> R.P. </span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="{{ route('patients.lab-received.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Lab Received--}}
{{--                <span class="label label-warning pull-right"> Lab Received </span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="{{ route('patients.positive.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Positive--}}
{{--                <span class="label label-danger pull-right">Positive</span>--}}

{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="{{ route('patients.negative.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Negative--}}
{{--                <span class="label label-success pull-right">Negative</span>--}}

{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="{{ route('patients.tracing.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Contact Tracing--}}
{{--                <span class="label label-primary pull-right">Tracing</span>--}}

{{--            </a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</li>--}}
{{--<li>--}}
{{--    <a href="#">--}}
{{--        <i class="fa fa-users" aria-hidden="true"></i>--}}

{{--        CLOSED CASES <span class="fa arrow"></span>--}}
{{--    </a>--}}

{{--    <ul class="nav nav-second-level">--}}
{{--        <li>--}}
{{--            <a href="{{ route('cases.recovered.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Recovered / Discharged--}}
{{--                <span class="label label-success pull-right">R/D</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="{{ route('cases.death.index') }}">--}}
{{--                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>--}}
{{--                Deaths--}}
{{--                <span class="label label-warning pull-right"> Deaths </span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</li>--}}
<li class="active">
    <a href="#">
        <i class="fa fa-money" aria-hidden="true"></i>

        CASES Payment <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
                      <li>
                    <a href="{{ route('public.home.index') }}">
                        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        Public Portal
                        <span class="label label-info pull-right">Portal</span>
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

                    </ul>
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
        <li>
            <a href="#">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                Observation Cases <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level">
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