@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <h4 class="align-center"></h4>
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
                        FCHV's Overview
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>FCHV Name</th>
                                    <th>HP Name</th>
                                    <th>Phone</th>
                                    <th>District</th>
                                    <th>Register by selected date</th>
                                    <th>Total Register</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i = 0; @endphp
                                @foreach($data as $d)
                                    @php $i++ @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->getHealthpost($d->hp_code) }}</td>
                                        <td>{{ $d->phone }}</td>
                                        <td>{{ $d->getDistrictName($d->district_id) }}</td>
                                        <td>{{ $d->woman_monthly }} </td>
                                        <td>{{ $d->woman_total }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
@endsection