<li>
    <a href="{{ route('report.dashboard') }}">
        <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
        {{trans('sidebar.woman_information')}}
    </a>
</li>

<li>
    <a href="#">
        <i class="fa fa-files-o" aria-hidden="true"></i>
        {{trans('sidebar.report_hmis')}}<span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('report.safe-maternity-program') }}">
                - सुरक्षित मातृत्व कार्यक्रम (६)
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li>

        <li>
            <a href="{{ route('report.vaccination-program') }}">
                - खाेप कार्यक्रम (१)
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li>

        </li>

    </ul>
</li>
<li>
    <a href="#">
        <i class="fa fa-files-o" aria-hidden="true"></i>
        {{trans('sidebar.report_info_format')}} <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('woman.raw-details-about-maternal-and-newborn-infants-report') }}">
                मातृ तथा नवजात शिशु सम्बन्धि विवरण
                <span class="pull-right">HMIS 4.2</span>
            </a>
        </li>
        <li>
            <a href="{{ route('child.raw-details-about-vaccinated-child') }}">
                खोप विवरण
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('woman.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        {{trans('sidebar.woman')}}
    </a>
</li>
<li>
    <a href="{{ route('child.index') }}">
        <i class="fa fa-child"></i>
        {{trans('sidebar.baby')}} <span class=""></span>
    </a>
</li>
<li>
    <a href="{{ route('woman-self-evaluation.report') }}">
            <i class="fa fa-bar-chart" aria-hidden="true"></i> {{ trans('sidebar.self_evaluation') }}
    </a>
</li>
<li>
    <a href="{{ route('woman.map') }}">
        <i class="fa fa-globe" aria-hidden="true"></i>
        {{trans('sidebar.woman_map')}}
    </a>
</li>
