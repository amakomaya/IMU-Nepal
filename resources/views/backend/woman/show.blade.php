@extends('layouts.backend.app')

@section('content')

<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Woman : {{$data->name}}
                    </div>
                    <!-- /.panel-heading -->
                <div class="panel-body">
                <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                                <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                  <div class="clearfix"></div>
                  @include('backend.woman.report')
                </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection
