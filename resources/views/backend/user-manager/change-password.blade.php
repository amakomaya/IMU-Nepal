@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Change Password ({{$user->username}})
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" action="{{route('user-manager.change-paswword-update')}}" >
                                {{ csrf_field() }}

                                {{ method_field('PUT') }}
                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username" class="col-md-4 control-label">Username</label>

                                    <div class="col-md-6">
                                        <input id="username" type="text" class="form-control" name="username" value="{{!!old('username')? old('username') : $user->username }}" autofocus>

                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-4 control-label">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" >

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                        <input type="checkbox" onclick="TogglePassword()"> 
                                        <b>Show Password</b> 
                                    </div>
                                </div>



                                <div class="form-group{{ $errors->has('re_password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-4 control-label">Confirm Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="re_password" >

                                        @if ($errors->has('re_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('re_password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input  type="hidden" class="form-control" name="id" value="{{$user->id}}" >
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Submit
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
                    