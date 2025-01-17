@extends('layouts.backend.app')
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
                        Patient Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <form method="get" action="">
                                <div class="form-group col-lg-6">
                                    <div id ="from_to"></div>
                                    <div class="form-group col-sm-3">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover table-responsive" id="dataTable"  style="width:100%;">
                                <thead>
                                <tr>
                                    <th rowspan="2" width="10px"></th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Contact No</th>
                                    <th rowspan="2" width="30%">Case Management</th>
                                    <th rowspan="2">Register Date</th>
                                    <th colspan="4" class="text-center">Health Condition</th>
                                </tr>
                                <tr>
                                    <th>All Status</th>
                                    <th>Start Date</th>
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
                                            } elseif ($cases->is_death == 2) {
                                                $current_status = '<span class="label label-danger">Dead</span>';
                                            } else {
                                                $current_status = '<span class="label label-info">N/A</span>';
                                            }
                                        }
                                        $total_days = 0;
                                    @endphp

                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $cases->name }}</td>
                                        <td>{{ $cases->phone }}</td>
                                        <td>
                                            <strong title="Organization / Hospital Name || Patient ID in Hospital">Org. Name || ID :</strong> {{ $cases->organization->name }} || {{ $cases->hospital_id }}<br>
                                            <strong title="Covid Test From Lab Name || Patient ID in Lab">Lab Test From || ID :</strong> {{ $cases->lab_name }} || {{ $cases->lab_id }}
                                        </td>
                                        <td>{{ $cases->register_date_np }}</td>
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
                                                $date_split = explode("-",$item->date);

                                                $yy = $date_split[0];
                                            	$mm = $date_split[1];
                                            	$dd = $date_split[2];
                                                $date_np = Yagiten\Nepalicalendar\Calendar::eng_to_nep((int)$yy, (int)$mm, (int)$dd)->getYearMonthDay();
                                            @endphp
                                                {{ $date_np }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($conditions[$key] as $key2 => $item)
                                                @php
                                                    $next_date = array_key_exists($key2 + 1, $conditions[$key]) ? $conditions[$key][$key2 + 1]->date : $discharge_date;

                                                    $datetime1 = new DateTime($item->date);
                                                    $datetime2 = new DateTime($next_date);
                                                    if($datetime2 < $datetime1){
                                                        $days = $total_days = 0;
                                                    } else {
                                                        $interval = $datetime1->diff($datetime2);
                                                        $days_d = $interval->format('%a');
                                                        $days = array_key_exists($key2 + 1, $conditions[$key]) ? $days_d : $days_d + 1;
                                                        $total_days += $days;
                                                    }
                                                @endphp
                                                {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}<br>
                                            @endforeach
                                            <b>Total days: {{ $total_days }} {{ \Illuminate\Support\Str::plural('day', $total_days) }}</b>
                                        </td>
                                        <td>
                                            {!! $current_status !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row text-center">
                                {{ $payment_cases->appends(Request::except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
@endsection

@section('script')
<script src="{{ asset('js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "paging":   false,
            "info":     false,
            dom : 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
        });

        $.get( "{{route("admin.select-from-to")}}?from_date={{$from_date}}&to_date={{$to_date}}",function(data){
            $("#from_to").html(data);
        });
    });
</script>
@endsection