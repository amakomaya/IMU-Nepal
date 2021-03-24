@extends('layouts.backend.app')

@section('content')
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection
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
                <div class="panel-heading">
                    Update Vaccination Center
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.organization.update') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="organization_id" class="col-md-3 control-label">Organization</label>

                            <div class="col-md-7">
                                <select id="organization_id" class="form-control" name="organization_id" onchange="organizationOnchange($(this).val())">
                                    <option>Select Organization</option>
                                    @foreach($organizations as $organization)
                                    <option value="{{ $organization->id }}"> {{ $organization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-offset-8 col-md-4 " >
                                <a href="{{ url('admin/healthpost/create')  }}"><u>Create Organization</u></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vaccinationCenter_id" class="col-md-3 control-label">Vaccination Center</label>

                            <div class="col-md-7">
                                <select id="vaccinationCenter_id" class="form-control" name="vaccinationCenter_id" onchange="vaccinationCenterOnchange($(this).val())">
                                    <option>Select Vaccination Center</option>
                                    @foreach($vaccination_centers as $organization)
                                    <option value="{{ $organization->id }}"> {{ $organization->vaccination_center }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-5">
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
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $("#organization_id").select2();
    $("#vaccinationCenter_id").select2();
    // $searchfield.prop('disabled', true);
});
</script>
@endsection
