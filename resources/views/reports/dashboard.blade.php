@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            @include('reports.layouts.filter')
            {!! Charts::styles() !!}
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Dashboard
                    </div>
                    <div class="app">
                        {!! $chart->html() !!}
                    </div>
                    {!! Charts::scripts() !!}
                    {!! $chart->script() !!}
                </div>
            </div>
        </div>
    </div>
@endsection