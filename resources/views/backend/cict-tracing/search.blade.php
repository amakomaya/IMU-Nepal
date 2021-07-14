@extends('layouts.backend.app')
@section('content')

<div id="page-wrapper">
    <!-- /.row -->
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
                <div class="panel-heading text-center">
                    <strong>CICT Form</strong>
                </div>
                <div class="panel-body">
                    <form action="{{ route('cict-tracing.create') }}">
                        <div class="row">
                            <div class="col-lg-8 form-group">
                                <label>Search Case ID</label>
                                <input type="text" name="case_id" class="form-control" placeholder="Enter Case Id" value="{{ request()->get('case_id') }}"><br>
                                <button class="btn btn-info" title="Search CaseID">
                                    <i class="fa fa-search"> Search or Continue without Case ID</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
</div>
<!-- /#page-wrapper -->
@endsection