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
                        <div class="col-lg-12" style="margin-bottom: 10px;">
                            <form action="" method="GET">
                                <div id ="from_only"></div>
                                <div class="form-group col-sm-3">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <p style="padding-bottom: 15px;"><i>Note: This report shows the data from organizations of type 'PCR Lab Test Only' and 'PCR Lab & Treatment'.</i></p>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">List of Laboratories</th>
                                        <th colspan="2">PCR</th>
                                        <th colspan="2">Antigen</th>
                                        <th rowspan="2">Data from API</th>
                                    </tr>
                                    <tr>
                                        <th>Positive</th>
                                        <th>Negative</th>
                                        <th>Positive</th>
                                        <th>Negative</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $healthpost)
                                    <tr>
                                        <td>
                                            @php($id = App\User::where('token', $healthpost['token'])->first()->id ?? '')
                                            <form method="post" action="{{url('admin/user-manager/'.$id.'/login-as')}}" >
                                                {{csrf_field()}}
                                                <a href="#" onclick="this.parentNode.submit()">{{ $healthpost['name'] }}</a>
                                            </form>
                                        </td>
                                        <td>{{ isset($healthpost['pcr_postive_today']) ? $healthpost['pcr_postive_today'] : 0 }}</td>
                                        <td> {{ isset($healthpost['pcr_postive_today']) ? $healthpost['pcr_negative_today'] : 0 }}</td>
                                        <td> {{ isset($healthpost['pcr_postive_today']) ? $healthpost['antigen_positive_today'] : 0 }}</td>
                                        <td> {{ isset($healthpost['pcr_postive_today']) ? $healthpost['antigen_negative_today'] : 0 }}</td>
                                        <td> {{ isset($healthpost['pcr_postive_today']) ? $healthpost['api_today'] : 'No' }}</td>
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

        $.get( "{{route("admin.select-from-only")}}?from_date={{$from_date}}",function(data){
            $("#from_only").html(data);
        });
    });
</script>
@endsection

