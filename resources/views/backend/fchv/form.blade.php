@extends('layouts.backend.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection

@section('content')
    
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @if (isset($data))
                                Edit Lab User
                            @else
                                Create Lab User
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                			<form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @yield('methodField')

                				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको नाम लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Name</label>

                                    <div class="col-md-7">
                                        <input id="name" type="text" class="form-control" name="name" value="@yield('name')" >

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                                        <label for="post" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको पद लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Post</label>

                                        <div class="col-md-7">
                                            <input id="post" type="text" class="form-control" name="post" value="@yield('post')" >

                                            @if ($errors->has('post'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('post') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label for="image" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको सही (signature) फोटो खिचेर हाल्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i> Signature</label>

                                        <div class="col-md-7">
                                            @if(!empty($data->image))
                                                <img src="{{ Storage::url('health-worker/'.$data->image) }}" /> <br><br>
                                            @endif
                                            <input id="image" type="file" name="image" value="@yield('image')" >

                                            @if ($errors->has('image'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label for="phone" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको फोन नं हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Phone</label>

                                        <div class="col-md-7">
                                            <input id="phone" type="text" class="form-control" name="phone" value="@yield('phone')" >

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('tole') ? ' has-error' : '' }}">
                                        <label for="tole" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको गाउँ टोलको नाम लेख्नुहिस्।"class="fa fa-info-circle" aria-hidden="true"></i> Tole</label>

                                        <div class="col-md-7">
                                            <input id="tole" type="text" class="form-control" name="tole" value="@yield('tole')" >

                                            @if ($errors->has('tole'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tole') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!isset($data))
                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-md-3 control-label"><i data-toggle="tooltip" title="आमाकोमाया केयरको लागि Username लेख्नुहोस्। सजिलोको लागि स्वास्थ्यकर्मीको फोन नं हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Username</label>

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
                                            <label for="password" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Password</label>

                                            <div class="col-md-7">
                                                <input id="password" type="password" class="form-control" name="password" value="@yield('password')" >

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
                                            <label for="re_password" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड पुनः लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Confirm Password</label>

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
                                        <label for="email" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको इमेल आई.डी. हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Email</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="@yield('email')" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
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

                                    <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}" hidden>
                                        <label for="latitude" class="col-md-3 control-label">Latitude</label>

                                        <div class="col-md-7">
                                            <input id="latitude" type="text" class="form-control" name="latitude" value="@yield('latitude')" >

                                            @if ($errors->has('latitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('latitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" title="Status छान्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Status</label>
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
                                <hr>

                                <div class="form-group">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title="Enter Lab Full Information।"class="fa fa-info-circle" aria-hidden="true"></i> <strong>Lab Information</strong> </label>
                                <div class="col-md-7"></div>

                                </div>

                               <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
                                    <label for="province_id" class="col-md-3 control-label">Province</label>
                                    
                                    <div class="col-md-7" id="province">
                                    <select name="province_id" class="form-control" onchange="provinceOnchange($(this).val())">
                                        @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                            <option value="">Select All Provinces</option>
                                        @endif
                                        @foreach($provinces as $province)
                                            @if($province_id==$province->id)
                                                @php($selectedProvince = "selected")
                                            @else
                                                @php($selectedProvince = "")
                                            @endif
                                            <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                        @endforeach
                                    </select>

                                        @if ($errors->has('province_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}">
                                    <label for="district_id" class="col-md-3 control-label">District</label>
                                    
                                    <div class="col-md-7" id="district">
                                    <select name="district_id" class="form-control" onchange="districtOnchange($(this).val())">
                                        @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                            <option value="">Select All Districts</option>
                                        @endif
                                        @foreach($districts as $district)
                                            @if($district_id==$district->id)
                                                @php($selectedDistrict = "selected")
                                            @else
                                                @php($selectedDistrict = "")
                                            @endif
                                            <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                        @endforeach
                                    </select>

                                        @if ($errors->has('district_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('district_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}">
                                    <label for="municipality_id" class="col-md-3 control-label">Municipality</label>
                                    
                                    <div class="col-md-7" id="municipality">
                                    <select name="municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())"
                                            id="municipality_id">
                                        @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                            <option value="">Select All Municipalities</option>
                                        @endif
                                        @foreach($municipalities as $municipality)
                                            @if($municipality_id==$municipality->id)
                                                @php($selectedMunicipality = "selected")
                                            @else
                                                @php($selectedMunicipality = "")
                                            @endif
                                            <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                        @endforeach
                                    </select>

                                        @if ($errors->has('municipality_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('municipality_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    

                                <div class="form-group{{ $errors->has('hp_code') ? ' has-error' : '' }}">
                                    <label for="hp_code" class="col-md-3 control-label"><i data-toggle="tooltip" title=" नाम लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Unique Lab Code</label>

                                    <div class="col-md-7">
                                        <input id="hp_code" type="text" class="form-control" name="hp_code" value="<?php echo uniqid(); ?>" readonly>

                                        @if ($errors->has('hp_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hp_code') }}</strong>
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
                            
@section('script')
    <script type="text/javascript">
        function provinceOnchange(id) {
            $("#district").text("Loading...").fadeIn("slow");
            $.get("{{route("district-select-province")}}?id=" + id, function (data) {
                $("#district").html(data);
            });
        }

        function districtOnchange(id) {
            $("#municipality").text("Loading...").fadeIn("slow");
            $.get("{{route("municipality-select-district")}}?id=" + id, function (data) {
                $("#municipality").html(data);
            });
        }

        function municipalityOnchange(id) {
            $("#ward-or-healthpost").text("Loading...").fadeIn("slow");
            $.get("{{route("ward-or-healthpost-select-municipality")}}?id=" + id, function (data) {
                $("#ward-or-healthpost").html(data);
            });
        }
    </script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection