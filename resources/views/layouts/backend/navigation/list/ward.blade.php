<li>
    <a href="{{ route('report.dashboard') }}">
    <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
    {{trans('sidebar.woman_information')}} 
    </a>
</li>

{{--<li>--}}
{{--    <a href="{{ route('woman.information') }}">--}}
{{--    <i class="fa fa-info-circle" aria-hidden="true"></i>--}}
{{--        Woman Information--}}
{{--    </a>--}}
{{--</li>--}}
<li>
    <a href="#">
    <i class="fa fa-files-o" aria-hidden="true"></i>
    {{trans('sidebar.report_hmis')}}  <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('woman.td-vaccine-service') }}">
                - टि.डी.खोप विवरण
                <span class="label label-info pull-right">HMIS 2.24</span>
            </a>
        </li>
        <!-- <li>
            <a href="{{ route('woman.report.health-service-register') }}">
                - मातृ तथा नवशिशु स्वास्थ सेवा रेजिस्टर
                <span class="label label-info pull-right">HMIS 3.4</span>
            </a>
        </li> -->
        <!-- <li>
            <a href="{{ route('woman.security-program-of-mother')}}">
                - अामाको सूरक्षा कार्यक्रम
            <span class="label label-info pull-right">HMIS 3.62</span>
            </a>
        </li> -->
       
        <li>
            <a href="{{ route('woman.delivery-service-according-to-castes') }}">
                - जात/जाती अनुसार स्वास्थ्य संस्था प्रसुति सेवा समायोजन फारम
                <span class="label label-info pull-right">HMIS 3.63</span>

            </a>
        </li>
        
        
        <li>
            <a href="{{ route('woman.details-about-maternal-and-newborn-infants') }}">
                - मातृ तथा नवजात शिशु सम्बन्धि विवरण
                <span class="label label-info pull-right">HMIS 4.2</span>
            </a>
        </li>
        <!-- <li>
            <a href="{{ route('woman.vaccination-program') }}">
                - १. खोप कार्यक्रम <br>  टि.डी. (गर्भवती महिला)
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li> -->
        <li>
            <a href="{{ route('woman.safe-maternity-program') }}">
                - ६. सुरक्षित मातृत्व कार्यक्रम
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li>

        

        <li>
            <a href="{{ route('woman.vaccination-program') }}">
                - १. खाेप कार्यक्रम
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li> 


        <li>
            <a href="{{ route('woman.vaccination-detail-list') }}">
                खाेप 
                <span class="label label-info pull-right">HMIS 9.3</span>
            </a>
        </li> 

        <li>
            <a href="{{ route('child-report.vaccine-received-usage-wastage') }}">
                - भ्याक्सिन प्राप्त, खर्च तथा खेर गएको विवरण (डोजमा)
                <span class="label label-info pull-right">HMIS 2.22</span>
            </a>
        </li> 
        
    </ul>
</li>

<li>
    <a href="#">
    <i class="fa fa-files-o" aria-hidden="true"></i>
    {{trans('sidebar.report_info_format')}}  <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('child-report.registered-child') }}">
                {{trans('sidebar.registered_child')}}
            </a>
        </li>
        <li>
            <a href="{{ route('child-report.immunized-child') }}">
                 {{trans('sidebar.immunized_child')}}
            </a>
        </li>
        <li>
            <a href="{{ route('child-report.droupout-child') }}">
                Dropout Child
            </a>
        </li>
        <li>
            <a href="{{ route('child-report.eligible-child') }}">
                 {{trans('sidebar.eligible_child')}}
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
    <i class="fa fa-building-o" aria-hidden="true"></i>
        {{trans('sidebar.health_post')}} <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('healthpost.index') }}">{{trans('sidebar.list')}}</a>
        </li>
        <li>
            <a href="{{ route('healthpost.create') }}">{{trans('sidebar.create')}}</a>
        </li>
    </ul>
</li>