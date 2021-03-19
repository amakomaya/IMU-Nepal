@extends('layouts.backend.app')

@section('content')

<div id="page-wrapper">


    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Vaccination Center
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="http://127.0.0.1:8000/admin/municipality" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="m4GQzONoNMHKCHeDDtsHrm6plRHCtDYPsLjVAAZh">

                        <div class="form-group">
                            <label for="organization_id" class="col-md-3 control-label">Organization</label>

                            <div class="col-md-7">
                                <select id="organization_id" class="form-control" name="organization_id" onchange="organizationOnchange($(this).val())">
                                    <option value="">Select Province</option>
                                    <option value="1">kathmandu pvt ltd</option>
                                </select>

                            </div>
                        </div>
                        <!-- <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-offset-8 col-md-4 " >
                                <a href="">Create Organization</a>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label for="vaccinationCenter_id" class="col-md-3 control-label">vaccinationCenter</label>

                            <div class="col-md-7">
                                <select id="vaccinationCenter_id" class="form-control" name="vaccinationCenter_id" onchange="vaccinationCenterOnchange($(this).val())">
                                    <option value="">Select Province</option>
                                    <option value="1">vaccination pvt ltd</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create Organization
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Update Vaccination center
                                </button>
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