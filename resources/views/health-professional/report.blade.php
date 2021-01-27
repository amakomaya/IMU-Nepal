@extends('layouts.backend.app')
@section('style')

@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa- fa-3x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $data['vaccination_count'] }}</div>
                                <div>Total Immunization</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
@section('script')

@endsection