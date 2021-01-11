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
                    @if (Request::session()->has('message'))
                        <div class="alert alert-block alert-success">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            {!! Request::session()->get('message') !!}

                        </div>
                    @endif
                    <div class="panel-body">
                        <table class="table" id="table">
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
                </div>
            </div>
        </div>
    </div>
@endsection
