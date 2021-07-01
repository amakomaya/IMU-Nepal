@extends('layouts.backend.app')

@section('content')
<style>
/* show hints when focus the input */

input:focus ~ div{
  opacity: 1;
}

/* hidden by default */
.hint {
  content: "asd";
  opacity: 0;
  position: relative;
  top: 0px;
  left: 0px;
  padding: 5px;
  border: 0;
  font-size: 13px;
  color: #767676;
    -webkit-transition: all .25s ease-in-out .25s;
    -ms-transition: all .25s ease-in-out .25s;
    transition: all .25s ease-in-out .25s;
    display: block;
    opacity: 0;
    max-height: 0;
    overflow: hidden
}

</style>
    
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @if (isset($data))
                                Edit Organizations
                            @else
                                Create Organizations
                            @endif
                        </div>
                        @if (\Request::session()->has('message'))
                            <div class="alert alert-block alert-success">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                                {!! Request::session()->get('message') !!}
                            </div>
                        @endif
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                			<form class="form-horizontal" role="form" method="POST" action="@yield('action')" >
                                {{ csrf_field() }}
                                @yield('methodField')

                				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title="यहाँ स्वास्थ्य चौकीको नाम लेख्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i> {{trans('create.name')}}</label>                                    
                                    <div class="col-md-7">
                                        <input id="name" type="text" class="form-control" name="name" value="@yield('name')" >
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}" hidden>
                                <label for="province_id" class="col-md-3 control-label" >Province</label>
                                
                                <div class="col-md-7">
                                    <select id="province_id" class="form-control" name="province_id">
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
                                <label for="district_id" class="col-md-3 control-label" >District</label>
                                
                                <div class="col-md-7">
                                    <select id="district_id" class="form-control" name="district_id">
                                            @foreach ($districts as $district )
                                               <option value="{{ $district->id }}" @if($district_id=="$district->id") {{ 'selected' }} @endif >{{ $district->district_name }}</option>
                                            @endforeach
                                    </select>

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
                                    
                                    <select id="municipality_id" class="form-control" name="municipality_id" >
                                            <option value="{{ $municipalities->municipality_id }}" >{{ $municipalities->municipality_id }}</option>
                                    </select>

                                    @if ($errors->has('municipality_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('municipality_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                                 <div class="form-group{{ $errors->has('ward_no') ? ' has-error' : '' }}">
                                    <label for="ward_no" class="col-md-3 control-label">
                                    <i data-toggle="tooltip" title="यहाँबाट वार्ड छान्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>

                                    {{trans('create.ward_no')}}</label>
                                    
                                    <div class="col-md-7">
                                    <select id="ward_no" class="form-control" name="ward_no" >
                                            <option value="" selected disabled hidden>Select Ward</option>
                                            @for ($ward =1; $ward<=50; $ward++)
                                               <option value="{{ $ward }}" @if($ward_no=="$ward") {{ 'selected' }} @endif >{{ $ward }}</option>
                                            @endfor
                                    </select>

                                        @if ($errors->has('ward_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('ward_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    @if (!isset($data))
                                    
                                    <div class="form-group{{ $errors->has('hp_code') ? ' has-error' : '' }} form-item">
                                        <label for="hp_code" class="col-md-3 control-label"><i data-toggle="tooltip" title="यहाँ तपाईले प्रान्तको कोड -जिल्लाको कोड-नगरपालिकाको कोड-स्वास्थ्य चौकीको कोड लेख्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i>
                                        {{trans('create.healthpost_code')}}</label>
                                         
                                        <div class="col-md-7">
                                            <input id="hp_code" type="text" class="form-control" name="hp_code" value="{{ $provinces[0]['id'] }}-{{ $districts[0]['id'] }}-{{ $municipalities->id }}-{{ Illuminate\Support\Str::upper(Illuminate\Support\Str::random(2)) }}" placeholder="eg. {{ $provinces[0]['id'] }}-{{ $districts[0]['id'] }}-{{ $municipalities->id }}-{{ Illuminate\Support\Str::upper(Illuminate\Support\Str::random(2)) }}" readonly>

                                            @if ($errors->has('hp_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('hp_code') }}</strong>
                                                </span>
                                            @endif
                                            <p class="text-danger hint">This paragraph is styled with class "text-danger".</p>            

                                        </div>

                                    </div>
                                    @endif

                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label for="phone" class="col-md-3 control-label"><i data-toggle="tooltip" title=" स्वास्थ्य चौेेकीको फोन नंम्बर हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                        {{trans('create.phone')}}</label>

                                        <div class="col-md-7">
                                            <input id="phone" type="text" class="form-control" name="phone" value="@yield('phone')" >

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!isset($data))
                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-md-3 control-label"><i data-toggle="tooltip" title=" प्रयोगकर्ताको नाम लेख्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i>
                                            {{trans('create.username')}}</label>

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
                                            <label for="password" class="col-md-3 control-label"><i data-toggle="tooltip" title="यहाँ तपाई सम्झनसक्ने पासवर्ड हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                            {{trans('create.password')}}</label>

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
                                            <label for="re_password" class="col-md-3 control-label"><i data-toggle="tooltip" title=" पुनः पासवर्ड लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                            {{trans('create.confirm_password')}}</label>

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
                                        <label for="email" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्य चौकी को ई-मेल आई. डी. हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                        {{trans('create.email')}}</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="@yield('email')" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                				<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                    <label for="address" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्य चैकीको ठेगाना लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                    {{trans('create.address')}}</label>

                                    <div class="col-md-7">

                                        <textarea id="address" type="text" class="form-control" name="address" >@yield('address')</textarea>

                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('hmis_uid') ? ' has-error' : '' }}" hidden>
                                    <label for="hmis_uid" class="col-md-3 control-label"><i data-toggle="tooltip" title="यहाँ तपाईलाई HMIS मा डाटा पठाउने यूनिक आई. डी. थाहा छ भने हाल्नुहोस् यदि थाहा छैन भने छोड्दिनुहोस्।  "class="fa fa-info-circle" aria-hidden="true"></i>
                                    {{trans('create.hmis_uid')}}</label>

                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="hmis_uid" value="@yield('hmis_uid')" >
                                        @if ($errors->has('hmis_uid'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hmis_uid') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                    <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}" hidden>
                                        <label for="longitude" class="col-md-3 control-label">Longitude</label>

                                        <div class="col-md-7">
                                            <input id="longitude" type="text" class="form-control" name="longitude" value="@yield('longitude')" >

                                            @if ($errors->has('longitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('longitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('lattitude') ? ' has-error' : '' }}" hidden>
                                        <label for="lattitude" class="col-md-3 control-label">Lattitude</label>

                                        <div class="col-md-7">
                                            <input id="lattitude" type="text" class="form-control" name="lattitude" value="@yield('lattitude')" >

                                            @if ($errors->has('lattitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('lattitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
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
                                    <label for="daily_consumption_of_oxygen" class="col-md-3 control-label"><i data-toggle="tooltip" title=""class="fa fa-info-circle" aria-hidden="true"></i>Daily Consumption of Oxygen ( Cylinder in liter )</label>

                                    <div class="col-md-7">
                                        <input id="daily_consumption_of_oxygen" type="text" class="form-control" name="daily_consumption_of_oxygen" value="{{ $data->daily_consumption_of_oxygen ?? '' }}" >

                                        @if ($errors->has('daily_consumption_of_oxygen'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('daily_consumption_of_oxygen') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('hospital_type') ? ' has-error' : '' }}">
                                    <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" class="fa fa-info-circle" aria-hidden="true"></i>
                                        Organization Type</label>
                                    @php($list = (new \App\Models\Organization())->array_organization_type)
                                    <div class="col-md-7">
                                        <select id="status" class="form-control" name="hospital_type" >
                                            <option value="" hidden>Select Organization Type</option>
                                            @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($hospital_type=="$key") {{ 'selected' }} @endif >
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('hospital_type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hospital_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('sector') ? ' has-error' : '' }}">
                                    <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" class="fa fa-info-circle" aria-hidden="true"></i>
                                        Organization Sector</label>
                                    <div class="col-md-7">
                                        <select id="sector" class="form-control" name="sector" >
                                            <option value="">Select Organization Sector</option>
                                            <option value="1" @if(isset($data->sector) && $data->sector == "1") {{ 'selected' }} @endif>Public</option>
                                            <option value="2" @if(isset($data->sector) && $data->sector == "2") {{ 'selected' }} @endif>Private</option>
                                        </select>

                                        @if ($errors->has('sector'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('sector') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्थिति छान्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i>
                                    {{trans('create.status')}}</label>
                                    @php($list = [1=>'Active',0=>'Inative'])
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
                                            {{ __('create.submit') }}
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