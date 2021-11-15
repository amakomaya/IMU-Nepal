@extends('layouts.backend.app')

@section('content')
    <script type="text/javascript">

    </script>
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12" style="margin-bottom: 20px;">
                <div class="col-md-3">
                    <div class="panel panel-danger">
                        <form action="" method="GET">
                        <select name="date_selected" class="form-control" onchange="this.form.submit()">
                            <option value="" disabled>Select Day</option>
                            <option value="1">Today</option>
                            <option value="2" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 2) selected @endif>Yesterday</option>
                            <option value="3" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 3) selected @endif>{{ \Carbon\Carbon::now()->subDays(2)->toDateString()}}</option>
                            <option value="4" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 4) selected @endif>{{ \Carbon\Carbon::now()->subDays(3)->toDateString()}}</option>
                            <option value="5" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 5) selected @endif>{{ \Carbon\Carbon::now()->subDays(4)->toDateString()}}</option>
                            <option value="6" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 6) selected @endif>{{ \Carbon\Carbon::now()->subDays(5)->toDateString()}}</option>
                            <option value="7" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 7) selected @endif>{{ \Carbon\Carbon::now()->subDays(6)->toDateString()}}</option>
                            <option value="8" @if(\Illuminate\Support\Facades\Request::query('date_selected') == 8) selected @endif >{{ \Carbon\Carbon::now()->subDays(7)->toDateString()}}</option>
                        </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Local Level Government Wise
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p style="padding-bottom: 15px;"><i>Note: This report shows the data based on the current address of patients.</i></p>

                        <div class="clearfix"></div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th rowspan="2">S.N</th>
                                    <th rowspan="2">District</th>
                                    <th rowspan="2">Municipality</th>
                                    <th colspan="2">PCR Test</th>
                                    <th colspan="2">Antigen Test</th>
                                </tr>
                                <tr>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $datum)
                                    <tr>
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ $datum['district_name'] }}</td>
                                        <td> {{ $datum['municipality_name'] }}</td>
                                        <td> {{ $datum['pcr_postive_cases_count'] }}</td>
                                        <td> {{ $datum['pcr_negative_cases_count'] }}</td>
                                        <td> {{ $datum['antigen_postive_cases_count'] }}</td>
                                        <td> {{ $datum['antigen_negative_cases_count'] }}</td>
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
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
@endsection
@section('Style')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/jquery.dataTables.min.css') }}"> --}}
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
                scrollX: true,
                pageLength: 50,
                dom : 'Bfrtip',
                buttons: [
                    'csv', 'excel',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ]
            });
        });
    </script>
@endsection

