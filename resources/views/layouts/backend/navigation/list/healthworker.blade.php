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
    <a href="{{ route('woman.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        COVID-19 Cases
        <span class="label label-info pull-right">Active</span>

    </a>
</li>
<li>
    <a href="{{ route('patients.negative.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        COVID-19 Cases
        <span class="label label-success pull-right">Negative</span>

    </a>
</li>
@endif