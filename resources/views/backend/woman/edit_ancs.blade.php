@extends('layouts.backend.app')

@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Anc
                    </div>

                    <div class="container">
                        <div id="accordion" class="panel-group">
                            <div class="card card-body">
                            <!-- form -->
                            <div class="panel panel-default">
                                <form class="form-group " method="post" action="{{route('ancs.ancs_update',$ancs->id)}}">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="visit_date" class="col-sm-2 control-label">Visited Date</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="visit_date"
                                                               value="{{$ancs->visit_date}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="weight" class="col-sm-2 control-label">Weight</label>

                                                    <div class="col-sm-10">
                                                        <input type="number" class="form-control" name="weight"
                                                               value="{{$ancs->weight}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="blood_pressure" class="col-sm-2 control-label">Blood Pressure</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="blood_pressure"
                                                               value="{{$ancs->blood_pressure}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="baby_heart_beat" class="col-sm-2 control-label">Baby Heart Beat</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="baby_heart_beat"
                                                               value="{{$ancs->baby_heart_beat}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="iron_pills" class="col-sm-2 control-label">Iron Pills</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="iron_pills"
                                                               value="{{$ancs->iron_pills}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->

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
    </div>



@endsection
