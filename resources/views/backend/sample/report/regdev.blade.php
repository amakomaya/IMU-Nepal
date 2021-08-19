@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Registered Device Report
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-md-3" style="margin-bottom: 40px;">
                            <form action="" method="GET">
                                <select class="form-control" name="date_selected" id="date_selected">
                                    <option value="1" @if(Request::get('date_selected') == '1') selected @endif>Today</option>
                                    <option value="2" @if(Request::get('date_selected') == '2') selected @endif>Yesterday</option>
                                    <option value="3" @if(Request::get('date_selected') == '3') selected @endif>{{ Carbon\Carbon::now()->subDays(2)->toDateString() }}</option>
                                    <option value="4" @if(Request::get('date_selected') == '4') selected @endif>{{ Carbon\Carbon::now()->subDays(3)->toDateString() }}</option>
                                    <option value="5" @if(Request::get('date_selected') == '5') selected @endif>{{ Carbon\Carbon::now()->subDays(4)->toDateString() }}</option>
                                    <option value="6" @if(Request::get('date_selected') == '6') selected @endif>{{ Carbon\Carbon::now()->subDays(5)->toDateString() }}</option>
                                    <option value="7" @if(Request::get('date_selected') == '7') selected @endif>{{ Carbon\Carbon::now()->subDays(6)->toDateString() }}</option>
                                </select>
                            </form>
                        </div>

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Organization Name</th>
                                        <th colspan="4" class="text-center">Total Data</th>
                                    </tr>
                                    <tr>
                                        <th>Web</th>
                                        <th>Mobile</th>
                                        <th>Api</th>
                                        <th>Excel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($healthposts as $healthpost)
                                    <tr>
                                        <td>
                                            {{ $healthpost['name'] }}
                                        </td>
                                        <td>
                                            {{ isset($data[$healthpost['hp_code']]) ? $data[$healthpost['hp_code']]['web_count'] : 0 }}
                                        </td>
                                        <td>
                                            {{ isset($data[$healthpost['hp_code']]) ? $data[$healthpost['hp_code']]['mobile_count'] : 0 }}
                                        </td>
                                        <td>
                                            {{ isset($data[$healthpost['hp_code']]) ? $data[$healthpost['hp_code']]['api_count'] : 0 }}
                                        </td>
                                        <td>
                                            {{ isset($excel_count[$healthpost['hp_code']]) ? $excel_count[$healthpost['hp_code']]->count() : 0 }}
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
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
@endsection

@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">

@endsection
@section('script')
<script>
$(function() {
    $('#date_selected').change(function() {
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

