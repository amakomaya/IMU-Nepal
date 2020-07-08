@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Lab Test
                    </div>

                    <div class="container">
                        <div id="accordion" class="panel-group">
                            <div class="card card-body">
                                <!-- form -->

                                <form class="form-group " method="post" action="{{route('labtest.labtest',$test->id)}}">
                                    @csrf

                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_date" class="col-sm-2 control-label">Test Date</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="test-date"
                                                               value="{{$test->test_date}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="urine_protein" class="col-sm-2 control-label">Urine Protein</label>

                                                    <div class="col-sm-10">
                                                        <input type="tel" class="form-control" name="urine_protein"
                                                               value="{{$test->urine_protein}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="blood_suger" class="col-sm-2 control-label">Blood Suger</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="blood_suger"
                                                               value="{{$test->blood_suger}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="urine_sugar" class="col-sm-2 control-label">Urine Sugar</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="urine_sugar"
                                                               value="{{$test->urine_sugar}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="retro_virus" class="col-sm-2 control-label">Retro Virus</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="retro_virus"
                                                               value="{{$test->retro_virus}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_by" class="col-sm-2 control-label">Test By</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="test_by"
                                                               value="{{$test->test_by}}">
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




