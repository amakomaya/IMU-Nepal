@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <div class="row" style="padding: 15px;">
        @if (Request::session()->has('message'))
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon-remove"></i>
                </button>
                {!! Request::session()->get('message') !!}
                <br>
            </div>
        @endif
        <h3 class="align-center">Last 24 hours Update</h3>
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <div class="huge">{{ $data['last_24_hrs_register'] }}</div>
                    <div><h4>Register</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['last_24_hrs_sample_collection'] }}</div>
                        <div><h4>Sample Collection </h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['last_24_hrs_lab_received_count'] }}</div>
                        <div><h4>Received In Lab</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="padding: 15px;">
    <h3 class="align-center">Total Records</h3>
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <div class="huge">{{ $data['total_register'] }}</div>
                    <div><h4>Total Register</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['total_sample_collection'] }}</div>
                        <div><h4>Total Sample Collection </h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
      <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['total_lab_received'] }}</div>
                        <div><h4>Received In Lab</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" style="padding: 15px;">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['mild_cases_home'] }}</div>
                        <div><h4>Asymptomatic / Mild Case</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['severe_cases_home'] }}</div>
                        <div><h4>Moderate / Severe Case</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /#page-wrapper -->
@endsection