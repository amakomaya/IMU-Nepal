@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @if (isset($data))
                                Edit Ward
                            @else
                            {{trans('create.create_ward')}}
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                			<form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @yield('methodField')

                    				<div class="form-group{{ $errors->has('ward_no') ? ' has-error' : '' }}">
                                        <label for="ward_no" class="col-md-3 control-label"><i data-toggle="tooltip" title=" वार्ड नंम्बर हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.ward_no')}}</label>

                                        <div class="col-md-7">
                                            <input id="ward_no" type="text" class="form-control" name="ward_no" value="@yield('ward_no')" >

                                            @if ($errors->has('ward_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('ward_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label for="phone" class="col-md-3 control-label"> <i data-toggle="tooltip" title=" फोन नंम्बर हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.phone')}}</label>

                                        <div class="col-md-7">
                                            <input id="phone" type="text" class="form-control" name="phone" value="@yield('phone')" >

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }} hidden">
                                    <label for="province_id" class="col-md-3 control-label">Province</label>
                                    
                                    <div class="col-md-7">
                                    <select id="province_id" class="form-control" name="province_id" >
                                            @foreach ($provinces as $province )
                                               <option value="{{ $province->id }}" @if($province_id=="$province->id") {{ 'selected' }} @endif >{{ $province->province_name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('province_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}" hidden>
                                    <label for="district_id" class="col-md-3 control-label">District</label>
                                    
                                    <div class="col-md-7">
                                        <div id="district">
                                            <select id="district_id" class="form-control" name="district_id">
                                                    @foreach ($districts as $district )
                                                       <option value="{{ $district->id }}" @if($district_id=="$district->id") {{ 'selected' }} @endif >{{ $district->district_name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        @if ($errors->has('district_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('district_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}" hidden>
                                    <label for="municipality_id" class="col-md-3 control-label">Municipality</label>
                                    
                                    <div class="col-md-7">
                                        <div id="municipality">
                                            <select id="municipality_id" class="form-control" name="municipality_id">
                                                    @foreach ($municipalities as $municipality )
                                                       <option value="{{ $municipality->id }}" @if($municipality_id=="$municipality->id") {{ 'selected' }} @endif >{{ $municipality->municipality_name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        @if ($errors->has('municipality_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('municipality_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                    <div class="form-group{{ $errors->has('office_address') ? ' has-error' : '' }}">
                                        <label for="office_address" class="col-md-3 control-label"><i data-toggle="tooltip" title=" वार्डको ठेगाना लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.office_address')}}</label>

                                        <div class="col-md-7">
                                            <input id="office_address" type="text" class="form-control" name="office_address" value="@yield('office_address')" >

                                            @if ($errors->has('office_address'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('office_address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!isset($data))
                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-md-3 control-label"><i data-toggle="tooltip" title="प्रयोगकर्ताको नाम लेख्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.username')}}</label>

                                            <div class="col-md-7">
                                                <input id="username" type="text" class="form-control" name="username" value="@yield('username')" >

                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-3 control-label"><i data-toggle="tooltip" title="यहाँ तपाई सम्झनसक्ने पासवर्ड हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.password')}}</label>

                                            <div class="col-md-7">
                                                <input id="password" type="password" class="form-control" name="password" value="@yield('password')" >

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                                <input type="checkbox" onclick="TogglePassword()"> 
                                                <b>{{trans('create.show_password')}}</b> 
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('re_password') ? ' has-error' : '' }}">
                                            <label for="re_password" class="col-md-3 control-label"><i data-toggle="tooltip" title=" पुनः पासवर्ड लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.confirm_password')}}</label>

                                            <div class="col-md-7">
                                                <input id="re_password" type="password" class="form-control" name="re_password" value="@yield('re_password')" >

                                                @if ($errors->has('re_password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('re_password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif



                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-3 control-label"><i data-toggle="tooltip" title=" वार्डको ई-मेल आई. डी. हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.email')}}</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="@yield('email')" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('office_longitude') ? ' has-error' : '' }}" hidden>
                                        <label for="office_longitude" class="col-md-3 control-label">Office Longitude</label>

                                        <div class="col-md-7">
                                            <input id="office_longitude" type="text" class="form-control" name="office_longitude" value="@yield('office_longitude')" >

                                            @if ($errors->has('office_longitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('office_longitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('office_lattitude') ? ' has-error' : '' }}" hidden>
                                        <label for="office_lattitude" class="col-md-3 control-label">Office Lattitude</label>

                                        <div class="col-md-7">
                                            <input id="office_lattitude" type="text" class="form-control" name="office_lattitude" value="@yield('office_lattitude')" >

                                            @if ($errors->has('office_lattitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('office_lattitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्थिति छान्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.status')}}</label>
                                    @php($list = [1=>'Active',0=>'Inactive'])
                                    <div class="col-md-7">
                                    <select id="status" class="form-control" name="status" >
                                            <option value="">Select Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($status=="$key")               {{ 'selected' }} @endif >
                                                   {{ $value }}
                                               </option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            {{trans('create.submit')}}
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
                          