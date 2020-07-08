@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
				    {!! rcForm::open('POST', route('appointment.plan.update', $row->id)) !!}
                    {{ method_field('PATCH') }}
                    {!! rcForm::selectOptions('Choose Service', 'plan', ['1' => 'Woman Service', '2' => 'Immunization'], $row->typePlan($row->plan)) !!}
                    {!! rcForm::text('Place', 'place', $row->place) !!}
                    {!! rcForm::text('Date From', 'from', $row->from) !!}
                    {!! rcForm::text('Date To', 'to', $row->to) !!}
                    {!! rcForm::text('Start Time', 'start_time', $row->start_time) !!}
                    {!! rcForm::text('End Time', 'end_time', $row->end_time) !!}
                    {!! rcForm::text('Duration ', 'duration', $row->duration) !!}
                    {!! rcForm::text('Total Seat', 'total_seat', $row->total_seat) !!}
                    {!! rcForm::close('PATCH') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection