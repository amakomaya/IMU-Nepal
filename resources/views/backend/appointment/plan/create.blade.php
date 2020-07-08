@extends('layouts.backend.app')
@section('style')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
            <div class="col-lg-12">
                    <div class="form-group">
                    <a class="btn btn-success" href="{{route('appointment.plan.index') }}">View All Plans</a>
                </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
					{!! rcForm::open('POST', route('appointment.plan.store')) !!}
                    {!! rcForm::selectOptions('Choose Service', 'plan', ['1' => 'Woman Service', '2' => 'Immunization']) !!}
                    {!! rcForm::text('Place', 'place') !!}
                    <div class="form-group">
                        <label for="datetime">Date Time</label>
                        <input type="text" class="form-control" name="datetimes" />
                    </div>
                    {{-- {!! rcForm::text('Date From', 'from') !!}
                    {!! rcForm::text('Date To', 'to') !!}
                    {!! rcForm::text('Start Time', 'start_time') !!}
                    {!! rcForm::text('End Time', 'end_time') !!} --}}
                    {!! rcForm::text('Duration ', 'duration') !!}
                    {!! rcForm::text('Total Seat', 'total_seat') !!}
					{!! rcForm::close('post') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'Y/M/DD hh:mm A'
    }
  });
});
</script>
@endsection
