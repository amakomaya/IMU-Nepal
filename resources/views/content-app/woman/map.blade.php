@extends('layouts.backend.app')

@section('content')

{!! $map['js'] !!}

<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
                <div class="panel panel-default">
                <div class="panel-heading">
                    Woman maps
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                        {!! $map['html'] !!}
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
@endsection

