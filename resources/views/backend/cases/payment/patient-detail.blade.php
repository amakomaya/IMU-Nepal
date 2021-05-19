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
                                    <th rowspan="2" width="10px"></th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Hospital ID</th>
                                    <th rowspan="2">Register Date</th>
                                    <th colspan="4" class="text-center">Health Condition</th>
                                </tr>
                                <tr>
                                    <th>All Status</th>
                                    <th>Admission Date</th>
                                    <th>Days Stayed</th>
                                    <th>Current Status</th>
                                </tr>
                                </thead>
                                @php $conditions = []; @endphp
                                <tbody>
                                    @foreach($payment_cases as $key => $cases)
                                    @php
                                        if($cases->health_condition_update != null) {
                                            $conditions[$key] = json_decode($cases->health_condition_update);
                                        } else {
                                            $conditions[$key] = [];
                                        }
                                        $first_data = [
                                            'id' => $cases->health_condition,
                                            'date' => date("Y-m-d", strtotime($cases->register_date_en)),
                                        ];
                                        $new = (object)$first_data;
                                        array_push($conditions[$key], $new);

                                        $lastvalue = end($conditions[$key]);
                                        $lastkey = key($conditions[$key]);

                                        $arr1 = array($lastkey=>$lastvalue);

                                        array_pop($conditions[$key]);

                                        $conditions[$key] = array_merge($arr1,$conditions[$key]);

                                        if($cases->date_of_outcome == null) {
                                            $discharge_date = date('Y-m-d');
                                        } else {
                                            $discharge_date = $cases->date_of_outcome_en;
                                        }

                                        if($cases->is_death == null) {
                                            $current_status = '<span class="label label-primary">Under Treatment</span>';
                                        } else {
                                            if($cases->is_death == 1){
                                                $current_status = '<span class="label label-success">Discharged</span>';
                                            } elseif ($cases->is_dead == 2) {
                                                $current_status = '<span class="label label-danger">Dead</span>';
                                            } else {
                                                $current_status = '<span class="label label-info">N/A</span>';
                                            }
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $cases->name }}</td>
                                        <td>{{ $cases->hospital_register_id }}</td>
                                        <td>{{ date("Y-m-d", strtotime($cases->register_date_np)) }}</td>
                                        <td>
                                            @foreach ($conditions[$key] as $key1 => $item)
                                            @php
                                                if($item->id == 1) {
                                                    $con_name = 'No Symptoms';
                                                } elseif ($item->id == 2) {
                                                    $con_name = 'Mild';
                                                } elseif ($item->id == 3) {
                                                    $con_name = 'Moderate ( HDU )';
                                                } elseif ($item->id == 4) {
                                                    $con_name = 'Severe - ICU';
                                                } elseif ($item->id == 5) {
                                                    $con_name = 'Severe - Ventilator';
                                                } else{
                                                    $con_name = 'N/A';
                                                }
                                            @endphp
                                                {{ $con_name }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($conditions[$key] as $key2 => $item)
                                            @php
                                                $yy = substr($item->date, 0, 4);
                                            	$mm = substr($item->date, 5, 2);
                                            	$dd = substr($item->date, 8, 2);
                                                $date_np = Yagiten\Nepalicalendar\Calendar::eng_to_nep((int)$yy, (int)$mm, (int)$dd)->getYearMonthDay();
                                            @endphp
                                                {{ $date_np }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($conditions[$key] as $key2 => $item)
                                                @php
                                                    $next_date = array_key_exists($key2 + 1, $conditions[$key]) ? $conditions[$key][$key2 +1]->date : $discharge_date;

                                                    $datetime1 = new DateTime($item->date);
                                                    $datetime2 = new DateTime($next_date);
                                                    $interval = $datetime1->diff($datetime2);
                                                    $days = $interval->format('%a');
                                                    $days = $days + 1;
                                                @endphp
                                                {{ $days }} days<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {!! $current_status !!}
                                        </td>
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
