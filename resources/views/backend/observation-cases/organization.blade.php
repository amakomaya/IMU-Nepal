@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        .card-body{
            margin-top: 10px;
            margin-left:10px;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
            padding:2px 16px;
            background-color: #d9edf7;
            color: #31708f;
        }
        .card-text {
            padding: 2px 16px;
            font-size: 20px;
        }
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}

                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Observation Cases
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="card card-body col-md-4">
                                <h5 class="card-title">Observation Cases/Emergency</h5>
                                <p class="card-text">{{ $add_sum - ($transfer_to_bed_sum + $return_to_home_sum) }}</p>
                            </div>
                            <div class="card card-body col-md-4">
                                <h5 class="card-title">Today's Observation Cases/Emergency</h5>
                                <p class="card-text">{{ $add_today_sum - ($transfer_to_bed_today_sum + $return_to_home_today_sum) }}</p>
                            </div>
                        </div>
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Add</th>
                                        <th>Transfer To Bed</th>
                                        <th>Return To Home</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($observation_cases as $key => $observation_case)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $observation_case->add }}</td>
                                        <td>{{ $observation_case->transfer_to_bed }}</td>
                                        <td>{{ $observation_case->return_to_home }}</td>
                                        <td>{{ $observation_case->created_at }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- <script type="text/javascript" src="{{ mix('js/app.js') }}"></script> --}}
@endsection
