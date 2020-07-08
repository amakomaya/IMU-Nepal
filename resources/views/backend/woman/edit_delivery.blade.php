@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Delivery
                    </div>

                    <div class="container">
                        <div id="accordion" class="panel-group">
                            <div class="card card-body">
                                <!-- form -->

                                <form class="form-group " method="post" action="{{route('delivery.delivery_update',$deliveries->id)}}">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_date" class="col-sm-2 control-label">Delivery Date</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="delivery_date"
                                                               value="{{$deliveries->delivery_date}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_time" class="col-sm-2 control-label">Delivery Time</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="delivery_time"
                                                               value="{{$deliveries->delivery_time}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_place" class="col-sm-2 control-label">Delivery Place</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="delivery_place"
                                                               value="{{$deliveries->delivery_place}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_by" class="col-sm-2 control-label">Delivery By</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="delivery_by"
                                                               value="{{$deliveries->delivery_by}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_type" class="col-sm-2 control-label">Delivery Type</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="delivery_type"
                                                               value="{{$deliveries->delivery_type}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="presentation" class="col-sm-2 control-label">Presentation</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="presentation"
                                                               value="{{$deliveries->presentation}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-info float-right">Submit</button>

                                        <!-- /.row -->
                                    </div>
                                    <!-- /.form group -->

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


