@extends('layouts.backend.app')

@section('content')

<script type="text/javascript" src="{{ asset('js/nepali.datepicker.v2.2.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/nepali.datepicker.v2.2.min.css') }}" />

<script type="text/javascript">
    $(document).ready(function(){
        $('#lmp_date_en').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10
        });
    });
</script>
<script type="text/javascript">
    function provinceOnchange(id){
            $("#district").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.district-select-province")}}?id="+id,function(data){
                $("#district").html(data);
            });
        }

    function districtOnchange(id){
        $("#municipality").text("Loading...").fadeIn("slow");
        $.get( "{{route("admin.municipality-select-district")}}?id="+id,function(data){
            $("#municipality").html(data);
        });
    }
</script>

<div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @if (isset($data))
                                @if(isset($pregancy))
                                    Register Pregnancy Again
                                @else
                                Edit Woman
                                @endif
                            @else
                                Create Woman
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



                            <div class="form-group{{ $errors->has('mool_darta_no') ? ' has-error' : '' }}">
                                <label for="mool_darta_no" class="col-md-3 control-label">Mool Darta No</label>

                                <div class="col-md-7">
                                    <input id="mool_darta_no" type="number" class="form-control" name="mool_darta_no" value="@yield('mool_darta_no')" >

                                    @if ($errors->has('mool_darta_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mool_darta_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('sewa_darta_no') ? ' has-error' : '' }}">
                                <label for="sewa_darta_no" class="col-md-3 control-label">Sewa Darta No</label>

                                <div class="col-md-7">
                                    <input id="sewa_darta_no" type="number" class="form-control" name="sewa_darta_no" value="@yield('sewa_darta_no')" >

                                    @if ($errors->has('sewa_darta_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sewa_darta_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('orc_darta_no') ? ' has-error' : '' }}">
                                <label for="orc_darta_no" class="col-md-3 control-label">Orc Darta No</label>

                                <div class="col-md-7">
                                    <input id="orc_darta_no" type="number" class="form-control" name="orc_darta_no" value="@yield('orc_darta_no')" >

                                    @if ($errors->has('orc_darta_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('orc_darta_no') }}</strong>
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

                            <div class="form-group{{ $errors->has('height') ? ' has-error' : '' }}">
                                        <label for="height" class="col-md-3 control-label">Height</label>

                                        <div class="col-md-7">
                                            <input id="height" type="text" class="form-control" name="height" value="@yield('height')" >

                                            @if ($errors->has('height'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('height') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                                        <label for="age" class="col-md-3 control-label">Age</label>

                                        <div class="col-md-7">
                                            <input id="age" type="number" class="form-control" name="age" value="@yield('age')" >

                                            @if ($errors->has('age'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('age') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('lmp_date_en') ? ' has-error' : '' }}">
                                        <label for="lmp_date_en" class="col-md-3 control-label">Lmp Date</label>

                                        <div class="col-md-7">
                                            <input id="lmp_date_en" type="text" class="form-control" name="lmp_date_en" value="@yield('lmp_date_en')" >

                                            @if ($errors->has('lmp_date_en'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('lmp_date_en') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('blood_group') ? ' has-error' : '' }}">
                                        <label for="blood_group" class="col-md-3 control-label">Blood Group</label>

                                        <div class="col-md-7">
                                            <input id="blood_group" type="text" class="form-control" name="blood_group" value="@yield('blood_group')" >

                                            @if ($errors->has('blood_group'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('blood_group') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
                                    <label for="province_id" class="col-md-3 control-label">Province</label>
                                    
                                    <div class="col-md-7">
                                    <select id="province_id" class="form-control" name="province_id" onchange="provinceOnchange($(this).val())">
                                            <option value="">Select Province</option>
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

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}">
                                    <label for="district_id" class="col-md-3 control-label">District</label>
                                    
                                    <div class="col-md-7">
                                        <div id="district" >
                                            <select id="district_id" class="form-control" name="district_id" onchange="districtOnchange($(this).val())">
                                                    <option value="">Select District</option>
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

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}">
                                    <label for="municipality_id" class="col-md-3 control-label">Municipality</label>
                                    
                                    <div class="col-md-7">
                                        <div id="municipality">
                                            <select id="municipality_id" class="form-control" name="municipality_id" onchange="municipalityOnchange($(this).val())">
                                                    <option value="">Select Municipality</option>
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


                                    <div class="form-group{{ $errors->has('tole') ? ' has-error' : '' }}">
                                        <label for="tole" class="col-md-3 control-label">Tole</label>

                                        <div class="col-md-7">
                                            <input id="tole" type="text" class="form-control" name="tole" value="@yield('tole')" >

                                            @if ($errors->has('tole'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tole') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('ward') ? ' has-error' : '' }}">
                                        <label for="ward" class="col-md-3 control-label">Ward</label>

                                        <div class="col-md-7">
                                            <input id="ward" type="text" class="form-control" name="ward" value="@yield('ward')" >

                                            @if ($errors->has('ward'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('ward') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('caste') ? ' has-error' : '' }}">
                                        <label for="caste" class="col-md-3 control-label">Caste</label>
                                        @php($list = \App\Models\Woman::$caste)
                                        <div class="col-md-7">
                                        <select id="caste" class="form-control" name="caste" >
                                                <option value="">Select Caste</option>
                                                    @foreach ($list as $key => $value )
                                                    <option value="{{ $key }}" @if($caste=="$key")               
                                                    {{ 'selected' }}
                                                     @endif >
                                                       {{ $value }}
                                                   </option>
                                                @endforeach
                                        </select>

                                            @if ($errors->has('caste'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('caste') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('husband_name') ? ' has-error' : '' }}">
                                        <label for="husband_name" class="col-md-3 control-label">Husband Name</label>

                                        <div class="col-md-7">
                                            <input id="husband_name" type="text" class="form-control" name="husband_name" value="@yield('husband_name')" >

                                            @if ($errors->has('husband_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('husband_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!isset($data))
                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-md-3 control-label">Username</label>

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
                                            <label for="password" class="col-md-3 control-label">Password</label>

                                            <div class="col-md-7">
                                                <input id="password" type="password" class="form-control" name="password" value="@yield('password')" >

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('re_password') ? ' has-error' : '' }}">
                                            <label for="re_password" class="col-md-3 control-label">Confirm Password</label>

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
                                        <label for="email" class="col-md-3 control-label">Email</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="@yield('email')" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                        <div class="form-group{{ $errors->has('anc_status') ? ' has-error' : '' }}">
                                    <label for="anc_status" class="col-md-3 control-label">Anc Status</label>
                                    @php($list = [1=>'Yes',0=>'No'])
                                    <div class="col-md-7">
                                    <select id="anc_status" class="form-control" name="anc_status" >
                                            <option value="">Select Anc Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($anc_status=="$key")               {{ 'selected' }} @endif >
                                                   {{ $value }}
                                               </option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('anc_status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('anc_status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                        <div class="form-group{{ $errors->has('delivery_status') ? ' has-error' : '' }}">
                                    <label for="delivery_status" class="col-md-3 control-label">Delivery Status</label>
                                    @php($list = [0=>'No',1=>'Delivery', 2=>'Misccarigae',])
                                    <div class="col-md-7">
                                    <select id="delivery_status" class="form-control" name="delivery_status" >
                                            <option value="">Select Delivery Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($delivery_status=="$key")               {{ 'selected' }} @endif >
                                                   {{ $value }}
                                               </option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('delivery_status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('delivery_status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('pnc_status') ? ' has-error' : '' }}">
                                    <label for="pnc_status" class="col-md-3 control-label">Pnc Status</label>
                                    @php($list = [1=>'Yes',0=>'No'])
                                    <div class="col-md-7">
                                    <select id="pnc_status" class="form-control" name="pnc_status" >
                                            <option value="">Select Pnc Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($pnc_status=="$key")               {{ 'selected' }} @endif >
                                                   {{ $value }}
                                               </option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('pnc_status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pnc_status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>



                                <div class="form-group{{ $errors->has('labtest_status') ? ' has-error' : '' }}">
                                    <label for="labtest_status" class="col-md-3 control-label">Labtest Status</label>
                                    @php($list = [1=>'Yes',0=>'No'])
                                    <div class="col-md-7">
                                    <select id="labtest_status" class="form-control" name="labtest_status" >
                                            <option value="">Select Labtest Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($labtest_status=="$key")               {{ 'selected' }} @endif >
                                                   {{ $value }}
                                               </option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('labtest_status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('labtest_status') }}</strong>
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

                                <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
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
                                    <label for="status" class="col-md-3 control-label">Status</label>
                                    @php($list = [1=>'Active',0=>'Inative'])
                                    <div class="col-md-7">
                                    <select id="status" class="form-control" name="status" >
                                            <option value="">Select Status</option>
                                                @foreach ($list as $key => $value )
                                                <option value="{{ $key }}" @if($status=="$key")               
                                                {{ 'selected' }}
                                                 @endif >
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
                                  