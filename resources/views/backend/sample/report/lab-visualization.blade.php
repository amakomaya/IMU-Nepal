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

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="3">List of Laboratories</th>
                                        <th colspan="5">Today's Update</th>
                                        <th colspan="5">Yesterday's Update</th>
                                    </tr>
                                <tr>
                                    <th colspan="2">PCR</th>
                                    <th colspan="2">Antigen</th>
                                    <th rowspan="2">Data from API</th>
                                    <th colspan="2">PCR</th>
                                    <th colspan="2">Antigen</th>
                                    <th rowspan="2">Data from API</th>
                                </tr>
                                <tr>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                    <th>Positive</th>
                                    <th>Negative</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $datum)
                                    <tr>
                                        <td>
                                            @php($id = App\User::where('token', $datum['healthpost_token'])->first()->id ?? '')
                                            <form method="post" action="{{url('admin/user-manager/'.$id.'/login-as')}}" >
                                                {{csrf_field()}}
                                                <a href="#" onclick="this.parentNode.submit()">{{ $datum['key'] }}</a>
                                            </form>
                                        </td>
                                        <td> {{ $datum['pcr_postive_today'] }}</td>
                                        <td> {{ $datum['pcr_negative_today'] }}</td>
                                        <td> {{ $datum['antigen_positive_today'] }}</td>
                                        <td> {{ $datum['antigen_negative_today'] }}</td>
                                        <td> {{ $datum['api_today'] }}</td>
                                        <td> {{ $datum['pcr_postive_yesterday'] }}</td>
                                        <td> {{ $datum['pcr_negative_yesterday'] }}</td>
                                        <td> {{ $datum['antigen_positive_yesterday'] }}</td>
                                        <td> {{ $datum['antigen_negative_yesterday'] }}</td>
                                        <td> {{ $datum['api_yesterday'] }}</td>
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

