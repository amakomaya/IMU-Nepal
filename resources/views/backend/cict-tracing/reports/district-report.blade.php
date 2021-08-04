@extends('layouts.backend.app')

@section('content')
    <script type="text/javascript">

    </script>
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Municipality Wise
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-lg-12" style="margin-bottom: 20px;">
                            <form action="" method="GET">
                                <div id ="from_only"></div>
                                <div class="form-group col-sm-3">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Municipality</th>
                                    <th>A Form</th>
                                    <th>B1 Form</th>
                                    <th>B2 Form</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $key => $location)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $location->municipality_name }}</td>
                                        <td>{{ isset($cict_tracings[$location->id]) ? $cict_tracings[$location->id]->count() : 0 }}</td>
                                        <td>{{ isset($contacts[$location->id]) ? $contacts[$location->id]->count() : 0 }}</td>
                                        <td>{{ isset($follow_ups[$location->id]) ? $follow_ups[$location->id]->count() : 0 }}</td>
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

            $.get( "{{route("admin.select-from-only")}}?from_date={{$from_date}}",function(data){
                $("#from_only").html(data);
            });
        });
    </script>
@endsection

