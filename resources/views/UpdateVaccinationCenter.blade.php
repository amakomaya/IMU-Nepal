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
            @if($errors->any())
                <div class="alert alert-block alert-danger">
                    Select both organization and vaccination center
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
                            <label for="organization_id" class="col-md-3 control-label">Organization created in IMU</label>

                            <div class="col-md-7">
                                <select id="organization_id" class="form-control" name="organization_id" onchange="organizationOnchange($(this).val())">
                                    <option value="">Select Organization</option>
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
                            <label for="vaccinationCenter_id" class="col-md-3 control-label">Vaccine Center created in vaccine2.mohp.gov.np</label>

                            <div class="col-md-7">
                                <select id="vaccinationCenter_id" class="form-control" name="vaccinationCenter_id" onchange="vaccinationCenterOnchange($(this).val())">
                                    <option value="">Select Vaccination Center</option>
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
            <hr>
                <table class="table">
                <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Organization created in IMU</th>
                    <th>Vaccine Center created in vaccine2.mohp.gov.np</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach(collect($organizations)->where('vaccination_center_id', '>', 0) as $org)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $org->name }}</td>
                    <td>{{ $vaccination_centers->values()->where('id', $org->vaccination_center_id)->first()->vaccination_center ?? '' }}</td>
                    <td>
                        <button type="button" class="btn btn-danger unlink-center" data-toggle="modal" data-target="#myModal"  data-url="{{ route('unlink.vaccination-center', ['id' => $org->id]) }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Warning</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center">Are you sure you want to remove the link between
                the organization and vaccination center.</h4>
                <div class="text-center">
                    <form id="unlinkForm" action="" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="margin-right: 25px;">Unlink</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $("#organization_id").select2();
    $("#vaccinationCenter_id").select2();
    // $searchfield.prop('disabled', true);

    $('.unlink-center').click(function () {
        var url = $(this).attr('data-url');
        $("#unlinkForm").attr("action", url);
        
        $('#myModal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var id = button.data('id');
            $('#unlinkForm').attr('action', url);
        });
    });

});
</script>
@endsection
