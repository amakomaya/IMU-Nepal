@if(App\Models\HealthWorker::where('token', auth()->user()->token)->first()->role == 'fchv')
    <li>
        <a href="{{ route('lab.patient.index') }}">
            <i class="fa fa-users" aria-hidden="true"></i>
            COVID-19 Cases
{{--            <span class="label label-info pull-right">Received</span>--}}
        </a>
    </li>

@else
    <li>
        <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>

            COVID-19 Cases <span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('woman.index') }}">
                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    Registered, Pending
                    <span class="label label-info pull-right"> R.P. </span>
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
        </ul>
    </li>

@endif