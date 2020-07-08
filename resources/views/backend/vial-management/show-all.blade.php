@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <h4 class="align-center">{{ $hp_name }}</h4>
                <strong>Report in the period of :</strong> <br><br>
                <form method="get" name="info">
                    <div class="from-group  row-sm-3" id="select-year-month">
                        <div id="app">
                            <select-year-month></select-year-month>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
        @section('script')
            <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
        @endsection
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Stock Details
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTabdle_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTsables-example">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Vaccine Name</th>
                                <th>Doses in Stock</th>
                                <th>New Dose (add)</th>
                                <th>Total dose after add new dose ( stock + new dose )</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stock_data as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->dose_in_stock }}</td>
                                <td>{{ $data->new_dose }}</td>
                                <td>{{ $data->dose_in_stock + $data->new_dose }}</td>
                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali(date('Y-m-d', strtotime($data->created_at))) }} {{ date('H:i:s', strtotime($data->created_at)) }}</td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Vaccine Vial Details
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Vaccine Name</th>
                                <th>Vial Image Name</th>
                                <th>Maximum Dose</th>
                                <th>Vial used ( in dose )</th>
                                <th>Vial wastage ( in dose )</th>
                                <th>Opened At</th>
                                <th>Created At</th>
                                <th>Vial Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vial_data as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->vaccine_name }}</td>
                                    <td>{{ $data->vial_image }}</td>
                                    <td>{{ $data->maximum_doses }}</td>
                                    <td>{{ $data->vial_used_doses }}</td>
                                    <td>{{ ($data->vial_used_doses !== 0) ? $data->vial_wastage_doses : '-' }}</td>
                                    <td>{{ ($data->vial_opened_date === '0000-00-00 00:00:00') ? 'Not Open Yet' : \App\Helpers\ViewHelper::convertEnglishToNepali(date('Y-m-d', strtotime($data->vial_opened_date))) .' '. date('H:i:s', strtotime($data->vial_opened_date)) }}</td>
                                    <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali(date('Y-m-d', strtotime($data->created_at))) }} {{ date('H:i:s', strtotime($data->created_at)) }}</td>
                                    <td>
                                        @if($data->vial_damaged_reason == '0')

                                        @elseif($data->vial_damaged_reason == '1')
                                            खसेको वा फुटेको
                                        @elseif($data->vial_damaged_reason == '2')
                                            घामले बिग्रेको
                                        @elseif($data->vial_damaged_reason == '3')
                                            डोज पुरा हुनु अघि सक्किएको
                                        @elseif($data->vial_damaged_reason == '4')
                                            अन्य समस्या
                                        @else
                                            {{ $data->vial_damaged_reason }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
@endsection
