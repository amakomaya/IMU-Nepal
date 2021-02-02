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
                        Municipalities
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
                                <th></th>
                                <th>ID</th>
                                <th title="Name">Name</th>
                                {{--                                <th><i class="fa fa-cogs" aria-hidden="true"></i>--}}
                                {{--                                </th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" id="dataList" value="{{ $d->id }}"
                                                   name="dataList[]">
                                        </div>
                                    </td>
                                    <td>{{$loop->iteration }}</td>
                                    <td>{{ $data  }}</td>
                                    {{--                                    <td>--}}
                                    {{--                                        <a title="View Health Professional"--}}
                                    {{--                                           href="{{ url('health-professional/show/'.$d->id) }}" target="_blank">--}}
                                    {{--                                            <i class="fa fa-file" aria-hidden="true"></i> |--}}
                                    {{--                                        </a>--}}
                                    {{--                                        <a title="Edit Health Professional Detail"--}}
                                    {{--                                           href="{{ url('health-professional/edit/'.$d->id) }}" target="_blank">--}}
                                    {{--                                            <i class="fa fa-edit" aria-hidden="true"></i>--}}
                                    {{--                                        </a>--}}
                                    {{--                                    </td>--}}
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