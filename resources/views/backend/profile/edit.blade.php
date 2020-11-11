@extends('layouts.backend.app')

@section('content')
    
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Profile
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                			<form class="form-horizontal" role="form" method="POST" action="{{ route('profile.update', '1') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    @method('PATCH')

                                @if(isset($data->name))
                				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title=" नाम लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Name</label>

                                    <div class="col-md-7">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ $data->name ?? '' }}" >

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i> Phone</label>

                                    <div class="col-md-7">
                                        <input id="phone" type="text" class="form-control" name="phone" value="{{ $data->phone ?? '' }}" >

                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-3 control-label"><i data-toggle="tooltip" title="इमेल आई.डी. हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Email</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="{{ $user->email ?? '' }}" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                
                                <div class="form-group{{ $errors->has('tole') ? ' has-error' : '' }}">
                                        <label for="tole" class="col-md-3 control-label"><i data-toggle="tooltip" title="गाउँ टोलको नाम लेख्नुहिस्।"class="fa fa-info-circle" aria-hidden="true"></i> Office Address</label>

                                        <div class="col-md-7">
                                            <input id="tole" type="text" class="form-control" name="tole" value="{{ $data->tole ?? '' }}" >

                                            @if ($errors->has('tole'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tole') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                           
                                   

                                    <br>
                                    <div class="form-group"> <div class="col-md-7 col-md-offset-3">
                                        
                                    <h4>Change Password</h4>
                                            You need to change password every month
                                        </div></div>
                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>New Password</label>

                                            <div class="col-md-7">
                                                <input id="password" type="password" class="form-control" name="password" value="" placeholder="leave it blank if you don't want to change it">

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                                <input type="checkbox" onclick="TogglePassword()"> 
                                                <b>Show Password</b> 
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="password_confirmation" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड पुनः लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Confirm new Password</label>

                                            <div class="col-md-7">
                                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" placeholder="leave it blank if you don't want to change it">

                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                 

                                    
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn-block btn btn-success">
                                            Update
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