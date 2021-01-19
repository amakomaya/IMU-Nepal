@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-info btn-sm pull-right"
                   href="{{ url('/health-professional/add') }}">
                    <i class="glyphicon glyphicon-plus"></i>Add Health Professional
                </a>
            </div>

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Health Professionals
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th title="Name">Name</th>
                                    <th title="Age">Age</th>
                                    <th title="Gender">Gender</th>
                                    <th title="Phone">Phone</th>
                                    <th title="Organization Type">Organization Type</th>
                                    <th title="Organization Name">Organization Name</th>
                                    <th><i class="fa fa-cogs" aria-hidden="true"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{$loop->iteration }}</td>
                                        <td>{{$d->name}}</td>
                                        <td>{{$d->age}}</td>
                                        <td>
                                            @if($d->gender === '1')
                                                Male
                                            @elseif($d->gender === '2')
                                                Female
                                            @elseif($d->gender === '3')
                                                Other
                                            @endif
                                        </td>
                                        <td>{{$d->phone}}</td>
                                        <td>
                                            @if($d->organization_type === '1')
                                                Government
                                            @elseif($d->organization_type === '2')
                                                Non-profit
                                            @elseif($d->organization_type === '3')
                                                Private
                                            @endif</td>
                                        <td>{{$d->organization_name}}</td>
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
