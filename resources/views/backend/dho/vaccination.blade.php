@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <a href="#" class="btn btn-success" id="btnSend" title="Send data to Immunization session"
                   data-toggle="modal"
                   data-target="#sendDataToImmunizationModel">
                    <i class="fa fa-paper-plane"></i>
                    Send</a>
            </div>

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if(auth()->user()->role == 'dho') Municipalities @else Organization @endif
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                            @if(auth()->user()->role == 'dho')
                                <table id="vaccinatedTable"
                                       class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th title="Municipality Name">Municipality Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($datas as $d)
                                        @php($token = $d['token'] ?? '' )
                                        @php($office_address = $d['office_address'] ?? '' )
                                        <tr>
                                            <td>
                                                <div class="checkbox">
                                                    <input type="checkbox" id="dataList" value="{{ $token }}"
                                                           name="dataList[]">
                                                </div>
                                            </td>
                                            <td>{{$loop->iteration }}</td>
                                            <td>{{ $office_address }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <table id="vaccinatedTable"
                                       class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th title="Name">Organization Name</th>
                                        <th title="Address">Address</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($datas as $d)
                                        @php($token = $d['token'] ?? '' )
                                        @php($office_address = $d['office_address'] ?? '' )
                                        <tr>
                                            <td>
                                                <div class="checkbox">
                                                    <input type="checkbox" id="dataList" value="{{ $token }}"
                                                           name="dataList[]">
                                                </div>
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d['name'] }}</td>
                                            <td>{{ $office_address }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            @endif


                        <div class="pull-right">
                            {{--                            {{ $data->links() }}--}}
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                @if(auth()->user()->role == 'dho')
                    <h3>All Organizations List</h3>
                    <div class="panel-body">
                        <table id="vaccinatedTable" class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th title="Name">Organization Name</th>
                                    <th title="Name">Organization Phone</th>
                                    <th title="Address">Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($org_user as $d)
                                    <tr>
                                        <td>
                                                <input type="checkbox" id="dataList" value="{{ $d->organization_name }}"
                                                       name="dataList[]">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->organization_name }}</td>
                                        <td>{{ $d->organization_phn }}</td>
                                        <td>{{ $d->organization_address }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                @endif

            </div>
            <!-- /.col-lg-12 -->
        </div>

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
                          action="{{ route('dho.vaccination.list.store') }}" enctype="multipart/form-data">
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
                $.get("{{route("health-professionals-list")}}?token=" + val, function (data) {
                    $("#data_list").val(data);
                });
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