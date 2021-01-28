@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Health Professionals
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table id="vaccinatedTable" class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Register No</th>
                                <th title="Name">Name</th>
                                <th title="Gender">Gender</th>
                                <th title="Age">Age</th>
                                {{--                                    <th>District</th>--}}
                                <th>Municipality</th>
                                <th>Ward</th>
                                <th title="Phone">Phone</th>
                                <th title="Designation">Post</th>
                                <th title="ID Number">ID Number</th>
                                <th><i class="fa fa-cogs" aria-hidden="true"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{$loop->iteration }}</td>
                                    <td>{{ str_pad($d->id, 6, "0", STR_PAD_LEFT) }}</td>
                                    <td>{{$d->name}}</td>
                                    <td>
                                        @if($d->gender === '1')
                                            Male
                                        @elseif($d->gender === '2')
                                            Female
                                        @elseif($d->gender === '3')
                                            Other
                                        @endif
                                    </td>
                                    <td>{{$d->age}}</td>
                                    {{--                                        <td>{{ $d->district->district_name ?? '' }}</td>--}}
                                    <td>{{ $d->municipality->municipality_name ?? '' }}</td>
                                    <td>{{ $d->ward ?? '' }}</td>
                                    <td>{{$d->phone}}</td>
                                    <td>{{$d->designation}}</td>
                                    <td>{{$d->citizenship_no .' / '. $d->issue_district}}</td>
                                    <td>
                                        <a title="View Health Professional"
                                           href="{{ url('health-professional/show/'.$d->id) }}">
                                            <i class="fa fa-file" aria-hidden="true"></i> |
                                        </a>
                                        <a title="Edit Health Professional Detail"
                                           href="{{ url('health-professional/edit/'.$d->id) }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ $data->links() }}
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready( function () {
            $('#vaccinatedTable').DataTable({
                paging: false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false
            });
        });
    </script>
@endsection