@extends('layouts.backend.app')

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
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as {{ \App\User::getAppRoleOnly() }} Admin !
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row" style="padding: 15px;">
    <div class="panel panel-default col-lg-6">
        <div class="panel-body">
                {!! $chartWoman->html() !!}
        </div>
    </div>
        <div class="panel panel-default col-lg-6">
            <div class="panel-body">
                {!! $chartBaby->html() !!}
            </div>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="panel panel-default col-lg-6">
            <div class="panel-body">
                Total ANC Visits : {{ $ancCount }}
            </div>
        </div>
        <div class="panel panel-default col-lg-6">
            <div class="panel-body">
                Total Baby Vaccination : {{ $vaccinatedBabyCount }}
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->
{!! $chartWoman->script() !!}
{!! $chartBaby->script() !!}
{!! Charts::scripts() !!}
@endsection
