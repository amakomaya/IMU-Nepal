@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        List
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @if (Request::session()->has('message'))
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="icon-remove"></i>
                                </button>
                                {!! Request::session()->get('message') !!}
                                <br>
                            </div>
                        @endif
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Information</th>
                                    <th>Organization</th>
                                    <th>Contact Info</th>
                                    <th>Username</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr class="@if($row->read_at == null) warning @endif">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            Name : {{ $row['data']['fullname'] }} <br>
                                            Designation : {{ $row['data']['designation'] }}
                                        </td>
                                        <td>{{ $row['data']['organization_name'] }}</td>
                                        <td>Email : {{ $row['data']['email'] }} <br>
                                            Phone : {{ $row['data']['phone'] }}
                                        </td>
                                        <td>{{ $row['data']['username'] }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-xs"
                                               id="changePassword" data-toggle="modal" data-target="#changePasswordModel"
                                               data-data="{{ $row }}"
                                            >
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                <span><strong>Change Password</strong></span>
                                            </a>
{{--                                            <form action="{{ route('notice-board.destroy', $row->id) }}" method="POST">--}}
{{--                                                @method('DELETE')--}}
{{--                                                @csrf--}}
{{--                                                <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>--}}
{{--                                            </form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="changePasswordModel" tabindex="-1" role="dialog" aria-labelledby="changePasswordModelLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                                        </div>
                                        <div class="modal-body">

                                            <form class="form-horizontal" role="form" method="POST" action="{{ route('password-reset-user.update') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="text" id="forgetPasswordId" name="forgetPasswordId" hidden>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-3" for="username">Username *:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="modelUsername" placeholder="Enter username" name="username" required readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-3" for="password">New Password *:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="password" placeholder="Enter new password" name="password" required minlength="4">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-9">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary pull-right">Submit Changes</button>
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

                            <!-- /.end model -->
                        </div>
                        <!-- /.table-responsive -->
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
    <script>
        $('body').on('click', '#changePassword', function (event) {
            event.preventDefault();
            var data = $(this).data('data');
            $('#modelUsername').val(data.data.username);
            $('#forgetPasswordId').val(data.id);
        });
    </script>
@endsection