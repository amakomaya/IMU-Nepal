@extends('layouts.backend.app')

@section('content')
    
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
                    @if (Session::get('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                    @endif
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

                                @if(auth()->user()->role === 'healthpost')
                                <div class="form-group{{ $errors->has('no_of_beds') ? ' has-error' : '' }}">
                                    <label for="no_of_beds" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i> No of Beds ( General )</label>

                                    <div class="col-md-7">
                                        <input id="no_of_beds" type="text" class="form-control" name="no_of_beds" value="{{ $data->no_of_beds ?? '' }}" >

                                        @if ($errors->has('no_of_beds'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('no_of_beds') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('no_of_hdu') ? ' has-error' : '' }}">
                                    <label for="no_of_hdu" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i>No of HDU</label>

                                    <div class="col-md-7">
                                        <input id="no_of_hdu" type="text" class="form-control" name="no_of_hdu" value="{{ $data->no_of_hdu ?? '' }}" >

                                        @if ($errors->has('no_of_hdu'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('no_of_hdu') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('no_of_icu') ? ' has-error' : '' }}">
                                    <label for="no_of_icu" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i> No of ICU </label>

                                    <div class="col-md-7">
                                        <input id="no_of_icu" type="text" class="form-control" name="no_of_icu" value="{{ $data->no_of_icu ?? '' }}" >

                                        @if ($errors->has('no_of_icu'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('no_of_icu') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('no_of_ventilators') ? ' has-error' : '' }}">
                                    <label for="no_of_ventilators" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i> No of Ventilators</label>

                                    <div class="col-md-7">
                                        <input id="no_of_ventilators" type="text" class="form-control" name="no_of_ventilators" value="{{ $data->no_of_ventilators ?? '' }}" >

                                        @if ($errors->has('no_of_ventilators'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('no_of_ventilators') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('daily_consumption_of_oxygen') ? ' has-error' : '' }}">
                                    <label for="daily_consumption_of_oxygen" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i> No of daily consumption of Oxygen</label>

                                    <div class="col-md-7">
                                        <input id="daily_consumption_of_oxygen" type="text" class="form-control" name="daily_consumption_of_oxygen" value="{{ $data->daily_consumption_of_oxygen ?? '' }}" >

                                        @if ($errors->has('daily_consumption_of_oxygen'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('daily_consumption_of_oxygen') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                @endif
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