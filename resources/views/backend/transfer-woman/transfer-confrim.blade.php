@extends('layouts.backend.app')

@section('content')

    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Admit Woman : {{$woman->name}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" action="{{route('transfer-woman.transfer-complete', $transfer->id)}}" >
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                       <label>Woman :</label> {{$woman->name}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <label>Refered By :</label> {{$healthpost->name}}
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <input type="hidden" name="from_hp_code" value="{{$healthpost->hp_code}}">
                                    <input type="hidden" name="woman_token" value="{{$woman->token}}">
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <input type="submit" class="btn btn-success" name="confirm" value="Confirm">
                                        <input type="submit" class="btn btn-danger" name="cancel" value="Cancel">
                                    </div>
                                </div>
                            </form>
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