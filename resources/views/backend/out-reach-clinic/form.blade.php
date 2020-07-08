@extends('layouts.backend.app')

@section('content')
    
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                @if (isset($data))
                    Edit Out Reach Clinics
                @else
                    Create Out Reach Clinics
                @endif
          </div>
        <!-- /.panel-heading -->
        <div class="panel-body">	
			<form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">
                {{ csrf_field() }}
                @yield('methodField')

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-3 control-label">Name</label>

                    <div class="col-md-7">
                        <input id="name" type="text" class="form-control" name="name" value="@yield('name')" >

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}" hidden="">
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

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}" hidden="">
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

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}" hidden="">
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


                    <div class="form-group{{ $errors->has('ward_no') ? ' has-error' : '' }}" hidden="">
                        <label for="ward_no" class="col-md-3 control-label">Ward No</label>

                        <div class="col-md-7">
                            <input id="ward_no" type="number" class="form-control" name="ward_no" value="@yield('ward_no')" >

                            @if ($errors->has('ward_no'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ward_no') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('hp_code') ? ' has-error' : '' }}" hidden="">
                        <label for="hp_code" class="col-md-3 control-label">Hp Code</label>

                        <div class="col-md-7">
                            <input id="hp_code" type="text" class="form-control" name="hp_code" value="@yield('hp_code')" >

                            @if ($errors->has('hp_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('hp_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                
				<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                    <label for="address" class="col-md-3 control-label">Address</label>

                    <div class="col-md-7">

                        <textarea id="address" type="text" class="form-control" name="address" >@yield('address')</textarea>

                        @if ($errors->has('address'))
                            <span class="help-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone" class="col-md-3 control-label">Phone</label>

                        <div class="col-md-7">
                            <input id="phone" type="text" class="form-control" name="phone" value="@yield('phone')" >

                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
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

                    <div class="form-group{{ $errors->has('lattitude') ? ' has-error' : '' }}">
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

                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label for="status" class="col-md-3 control-label">Status</label>
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
                                