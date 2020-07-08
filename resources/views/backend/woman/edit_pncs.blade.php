@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Pnc
                    </div>

                    <div class="container">
                        <div id="accordion" class="panel-group">
                            <div class="card card-body">
                                <!-- form -->

                                <form class="form-group " method="post" action="{{route('pncs.pncs_update',$pncs->id)}}">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date_of_visit" class="col-sm-2 control-label">Date of Visit</label>

                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" name="date_of_visit"
                                                               value="{{$pncs->date_of_visit}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="visit_time" class="col-sm-2 control-label">Visit Time</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="visit_time"
                                                               value="{{$pncs->visit_time}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mother_status" class="col-sm-2 control-label">Mother Status</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="mother_status"
                                                               value="{{$pncs->mother_status}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="baby_status" class="col-sm-2 control-label">Baby Status</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="baby_status"
                                                               value="{{$pncs->baby_status}}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="family_plan" class="col-sm-2 control-label">family Plan</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="family_plan"
                                                               value="{{$pncs->family_plan}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="iron_pills_amount" class="col-sm-2 control-label">Iron Pills Amount</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="iron_pills_amount"
                                                               value="{{$pncs->iron_pills_amount}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="checked_by" class="col-sm-2 control-label">Checked By</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="checked_by"
                                                               value="{{$pncs->checked_by}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vitamin_a" class="col-sm-2 control-label">Vitamin A</label>

                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="vitamin_a"
                                                               value="{{$pncs->vitamin_a}}">
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

