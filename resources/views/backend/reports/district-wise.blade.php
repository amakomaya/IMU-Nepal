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
                        District Wise
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover display dataTable" id="dataTable">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>District</th>
                                    <th>Information</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php ($count = 0)
                                {{ $data  }}
{{--                                @foreach(collect($data) as $key => $case)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ ++$count }}</td>--}}
{{--                                        <td>{{ $case['district_name'] }} </td>--}}
{{--                                        <td></td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
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

