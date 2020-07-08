@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Woman
                    </div>
                    <div class="container">
                        <div id="accordion" class="panel-group">
                            <div class="card card-body">
                                <!-- form -->
                                <form class="form-group " method="post" action="{{route('woman.woman_update',$woman->id)}}">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-2 control-label">Name</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="name"
                                                               value="{{$woman->name}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone" class="col-sm-2 control-label">Phone</label>

                                                    <div class="col-sm-10">
                                                        <input type="tel" class="form-control" name="phone"
                                                               value="{{$woman->phone}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="imp_date_en" class="col-sm-2 control-label">Lmp Date</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="imp_date_en"
                                                               value="{{$woman->lmp_date_en}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="blood_group" class="col-sm-2 control-label">Blood Group </label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="blood_group"
                                                               value="{{$woman->blood_group}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tole" class="col-sm-2 control-label">Tole</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="tole"
                                                               value="{{$woman->tole}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ward" class="col-sm-2 control-label">Ward No</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="ward"
                                                               value="{{$woman->ward}}">
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