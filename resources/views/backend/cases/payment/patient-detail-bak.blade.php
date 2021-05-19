@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}

                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Observation Cases
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th width="10px"></th>
                                    <th>Name</th>
                                    <th>Hospital ID</th>
                                    <th>Register Date</th>
                                    <th>Current Condition</th>
                                    <th>Past Condition</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment_cases as $key => $cases)
                                    @php
                                        $past_condition = '';
                                        if($cases->is_death == null) {
                                            if($cases->health_condition_update == null) {
                                                $condition = $cases->health_condition;
                                            } else {
                                                $condition_array = json_decode($cases->health_condition_update);
                                                $condition_latest = end($condition_array);
                                                $condition = $condition_latest->id;
                                            }

                                            if($condition == 1) {
                                                $con_name = 'No Symptoms';
                                            } elseif ($condition == 2) {
                                                $con_name = 'Mild';
                                            } elseif ($condition == 3) {
                                                $con_name = 'Moderate ( HDU )';
                                            } elseif ($condition == 4) {
                                                $con_name = 'Severe - ICU';
                                            } elseif ($condition == 5) {
                                                $con_name = 'Severe - Ventilator';
                                            } else{
                                                $con_name = 'N/A';
                                            }
                                        } else {
                                            if($case->is_death == 1){
                                                $con_name = 'Dead';
                                            } elseif ($case->is_dead == 2) {
                                                $con_name = 'Discharged';
                                            } else {
                                                $con_name = 'N/A';
                                            }
                                        }

                                       

                                        if($cases->health_condition_update == null) {
                                            $past_condition = '-';
                                        } else {
                                            $condition_array = json_decode($cases->health_condition_update);
                                            foreach ($condition_array as $key => $c_array) {
                                                if($c_array->id == 1) {
                                                    $c_name = 'No Symptoms';
                                                } elseif ($c_array->id == 2) {
                                                    $c_name = 'Mild';
                                                } elseif ($c_array->id == 3) {
                                                    $c_name = 'Moderate ( HDU )';
                                                } elseif ($c_array->id == 4) {
                                                    $c_name = 'Severe - ICU';
                                                } elseif ($c_array->id == 5) {
                                                    $c_name = 'Severe - Ventilator';
                                                } else{
                                                    $c_name = 'N/A';
                                                }

                                                $past_condition .= $c_name . ' Date: ' . $c_array->date . '<br>';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <td>{{ $cases->name }}</td>
                                        <td>{{ $cases->hospital_register_id }}</td>
                                        <td>{{ $cases->register_date_np }}</td>
                                        <td>{{ $con_name }}</td>
                                        <td>{!! $past_condition !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection
