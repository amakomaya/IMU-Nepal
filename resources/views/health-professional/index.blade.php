@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title">
                    Filter through
                </div>
            </div>
            <div class="panel-body">
                <form action="{{ route('health-professional.index') }}" method="GET" name="filter" id="filter">
                    <div class="row">
                        <div class="form-group col-lg-9">
                            <select name="organization" class="form-control" id="organization">
                                <option value="">Select an organization</option>
                                @foreach($organizations ?? '' as $immunizationCenter)
                                    <option value="{{ $immunizationCenter->token }}">{{$immunizationCenter->name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="#" class="btn btn-success"  id="btnDownload" data-toggle="modal" data-target="#healthProfessionalsDownloadModel">
                    <i class="fa fa-file-excel-o"></i>
                    Download Data</a>
                @if(Illuminate\Support\Facades\Auth::user()->role === "municipality")
                    <a href="#" class="btn btn-success" id="btnSend" title="Send data to Immunization session"
                       data-toggle="modal"
                       data-target="#sendDataToImmunizationModel">
                        <i class="fa fa-paper-plane"></i>
                        Send</a>
                @endif
                <a class="btn btn-info btn-sm pull-right"
                   href="{{ url('/health-professional/add') }}">
                    <i class="glyphicon glyphicon-plus"></i>Add Health Professional
                </a>
            </div>

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Health Professionals
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <table id="vaccinatedTable"
                               class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                @if(Illuminate\Support\Facades\Auth::user()->role === "municipality")
                                    <th></th>
                                @endif
                                <th>ID</th>
                                <th>Register No</th>
                                <th title="Name">Name</th>
                                <th title="Gender">Gender</th>
                                <th title="Age">Age</th>
                                {{--                                    <th>District</th>--}}
                                <th>Municipality</th>
                                <th>Ward</th>
                                <th title="Phone">Phone</th>
                                <th title="Designation">Post</th>
                                <th title="ID Number">ID Number</th>
                                <th><i class="fa fa-cogs" aria-hidden="true"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    @if(Illuminate\Support\Facades\Auth::user()->role === "municipality")
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox" id="dataList" value="{{ $d->id }}"
                                                       name="dataList[]">
                                            </div>
                                        </td>
                                    @endif
                                    <td>{{$loop->iteration }}</td>
                                    <td>{{ str_pad($d->id, 6, "0", STR_PAD_LEFT) }}</td>
                                    <td>{{$d->name}}</td>
                                    <td>
                                        @if($d->gender === '1')
                                            Male
                                        @elseif($d->gender === '2')
                                            Female
                                        @elseif($d->gender === '3')
                                            Other
                                        @endif
                                    </td>
                                    <td>{{$d->age}}</td>
                                    {{--                                        <td>{{ $d->district->district_name ?? '' }}</td>--}}
                                    <td>{{ $d->municipality->municipality_name ?? '' }}</td>
                                    <td>{{ $d->ward ?? '' }}</td>
                                    <td>{{$d->phone}}</td>
                                    <td>{{$d->designation}}</td>
                                    <td>{{$d->citizenship_no .' / '. $d->issue_district}}</td>
                                    <td>
                                        <a title="View Health Professional"
                                           href="{{ url('health-professional/show/'.$d->id) }}" target="_blank">
                                            <i class="fa fa-file" aria-hidden="true"></i> |
                                        </a>
                                        <a title="Edit Health Professional Detail"
                                           href="{{ url('health-professional/edit/'.$d->id) }}" target="_blank">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ $data->links() }}
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>
    <div class="modal fade" id="healthProfessionalsDownloadModel" tabindex="-1" role="dialog"
         aria-labelledby="healthProfessionalsDownloadModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Excel Download request form</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" name="excelExport" role="form" method="GET"
                          action="{{ route('health-professional.export') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group" hidden>
                            <input type="text" id="organization_token" name="organization_token" value="{{ $orgToken ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="created_at">Created At *:</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="from" placeholder="Enter from date"
                                       name="from" min="2019-01-01" max="<?php echo date('Y-m-d'); ?>"
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="to" placeholder="Enter to date" name="to"
                                       min="2019-01-01" max="<?php echo date('Y-m-d'); ?>"
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary pull-right">Submit Request</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    Note : * Required Fields
                </div>
            </div>
        </div>
    </div>

    @if(Illuminate\Support\Facades\Auth::user()->role === "municipality")
        <div class="modal fade" id="sendDataToImmunizationModel" tabindex="-1" role="dialog"
             aria-labelledby="sendDataToImmunizationModel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Send selected datas to Immunization session</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="excelExport" role="form" method="POST" id="create"
                              name="create"
                              action="{{ route('covid-immunization-store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group" hidden>
                                <input type="text" id="data_list" name="data_list">
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="hp_code">Select Immunization
                                    Center
                                    :</label>
                                <div class="col-sm-8">
                                    <select name="hp_code" class="form-control"
                                            id="hp_code">
                                        @foreach($organizations ?? '' as $immunizationCenter)
                                            <option value="{{ $immunizationCenter->hp_code }}">{{$immunizationCenter->name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="expire_date">Set Session Date :</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="expire_date" placeholder="Enter to date"
                                           name="expire_date"
                                           min="<?php echo date('Y-m-d'); ?>"
                                           value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary pull-right">Submit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        Note : * Required Fields
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#vaccinatedTable').DataTable({
                paging: false,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false
            });
            $('#btnSend').click(function () {
                var val = [];
                $(':checkbox:checked').each(function (i) {
                    val.push($(this).val());
                })
                $("#data_list").val(val.toString());
            });
        });

        $(function () {
            $("form[id='create']").validate({
                // Define validation rules
                rules: {
                    data_list: {
                        required: true,
                    },
                },

                // Specify validation error messages
                data_list: {
                    required: "You must select at least 1 data"
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });

            $("form[id='filter']").validate({
                // Define validation rules
                rules: {
                    organization: {
                        required: true,
                    }
                },
                // Specify validation error messages
                messages: {
                    organization: "Please select organization to filter",
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection