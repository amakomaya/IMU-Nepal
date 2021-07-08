@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Contact Tracing Report
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Organization Name</th>
                                        <th>Total Contact Tracing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $datum)
                                    <tr>
                                        <td> {{ $datum['organization_name'] }}</td>
                                        <td> {{ $datum['count'] }}</td>
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

