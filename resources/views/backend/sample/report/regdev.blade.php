@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Lab Visualizaion Report
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form action="" method="GET">
                            <select name="date" id="date_selected">
                                <option value="1">Today</option>
                                <option value="2">Yesterday</option>
                                <option value="3">{{ Carbon\Carbon::now()->subDays(2)->toDateString() }}</option>
                                <option value="4">{{ Carbon\Carbon::now()->subDays(3)->toDateString() }}</option>
                                <option value="5">{{ Carbon\Carbon::now()->subDays(4)->toDateString() }}</option>
                                <option value="6">{{ Carbon\Carbon::now()->subDays(5)->toDateString() }}</option>
                                <option value="7">{{ Carbon\Carbon::now()->subDays(6)->toDateString() }}</option>
                            </select>
                        </form>

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Organization Name</th>
                                        <th colspan="4">Total Data</th>
                                    </tr>
                                    <tr>
                                        <th>Web</th>
                                        <th>Mobile</th>
                                        <th>Api</th>
                                        <th>Excel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $datum)
                                    <tr>
                                        <td> {{ $datum['key'] }}</td>
                                        <td> {{ $datum['web_count'] }}</td>
                                        <td> {{ $datum['mobile_count'] }}</td>
                                        <td> {{ $datum['api_count'] }}</td>
                                        <td> {{ $datum['excel_count'] }}</td>
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
@endsection

@section('Style')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/jquery.dataTables.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">

@endsection
@section('script')
<script>
$(function() {
    $('#date_select').change(function() {
        this.form.submit();
    });
});
</script>

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
            pageLength: 50,
            dom : 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ]
        });
    });
</script>
@endsection

