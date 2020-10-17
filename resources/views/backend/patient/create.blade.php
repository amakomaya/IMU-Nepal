@extends('layouts.backend.app')
@section('style')
    <style>
        .earning {
            display: none;
        }
    </style>
@endsection
@section('content')

    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('woman.store')) !!}
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Is Detected From Contract Tracing ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_detected" {{ old('is_detected') == "no" ? 'checked' : '' }} value="no" data-rel="earning" checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" {{ old('is_detected') == "yes" ? 'checked' : '' }} name="is_detected" value="yes" data-rel="earning">Yes
                                    </label>
                                    <div class="earning form-group">
                                        <label class="form-label">Parent Case ID</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="{{ old('parent_case_id') }}" name="parent_case_id" placeholder="Enter Parent Case ID"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" aria-describedby="help" placeholder="Enter Full Name"
                                >
                                @if ($errors->has('name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                <label for="age">Age</label>
                                <input type="text" {{ old('age') }} class="form-control col-xs-9" name="age" placeholder="Enter Age"
                                ><br>
                                <input type="radio" name="age_unit" {{ old('age_unit') == "0" ? 'checked' : '' }} value="0" data-rel="earning" checked>Years
                                <input type="radio" name="age_unit" {{ old('age_unit') == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                                <input type="radio" name="age_unit" {{ old('age_unit') == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                                <br>
                                @if ($errors->has('age'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="caste">Caste</label>
                                    <select name="caste" class="form-control">
                                        <option {{ old('caste') == '0' ? "selected" : "" }} value="0">Don't Know</option>
                                        <option {{ old('caste') == '1' ? "selected" : "" }} value="1">Dalit</option>
                                        <option {{ old('caste') == '2' ? "selected" : "" }} value="2">Janajati</option>
                                        <option {{ old('caste') == '3' ? "selected" : "" }} value="3">Madheshi</option>
                                        <option {{ old('caste') == '4' ? "selected" : "" }} value="4">Muslim</option>
                                        <option {{ old('caste') == '5' ? "selected" : "" }} value="5">Brahmin/Chhetrai</option>
                                        <option {{ old('caste') == '6' ? "selected" : "" }} value="6">Other</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Gender</label>
                                <select name="sex" class="form-control">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option {{ old('sex') == '1' ? "selected" : "" }} value="1">Male</option>
                                    <option {{ old('sex') == '2' ? "selected" : "" }} value="2">Female</option>
                                    <option {{ old('sex') == '3' ? "selected" : "" }}  value="3">Other</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Current Address</label>
                                <div class="row">
                                <div class="form-group col-sm-3" id="province">
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
                                </div>
                                <div class="form-group  col-sm-3" id="district">
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
                                </div>
                                <div class="form-group  col-sm-3" id="municipality">
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
                                </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                                <label for="ward">Ward No</label>
                                <input type="text" class="form-control" name="ward" aria-describedby="help" placeholder="Enter Ward No"
                                >
                                @if ($errors->has('ward'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                                <label for="tole">Tole</label>
                                <input type="text" class="form-control" name="tole" aria-describedby="help" placeholder="Enter Tole"
                                >
                                @if ($errors->has('tole'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                <label for="name">Emergency Contact One</label>
                                <input type="text" class="form-control" name="emergency_contact_one" aria-describedby="help" placeholder="Enter Emergency Contact One"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                <label for="name">Emergency Contact Two</label>
                                <input type="text" class="form-control" name="emergency_contact_two" aria-describedby="help" placeholder="Enter Emergency Contact Two"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="caste">Occupation</label>
                                <select name="occupation" class="form-control">
                                    <option value="">Select Occupation</option>
                                    <option value="1">Front Line Health Worker</option>
                                    <option value="2">Doctor</option>
                                    <option value="3">Nurse</option>
                                    <option value="4">Police/Army</option>
                                    <option value="5">Business/Industry</option>
                                    <option value="6">Teacher/Student/Education</option>
                                    <option value="7">Journalist</option>
                                    <option value="8">Agriculture</option>
                                    <option value="9">Transport/Delivery</option>
                                    <option value="10">Other</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Have you traveled till 15 days ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="travelled" value="0"  checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="travelled" value="1">Yes
                                    </label>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Are you collecting COVID -19 swab now ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="swab_collection_conformation" value="0"  >No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="swab_collection_conformation" value="1" checked>Yes
                                    </label>

                                </div>
                            </div>

                        {!! rcForm::close('post') !!}
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
        $(':radio[data-rel]').change(function() {
            var rel = $("." + $(this).data('rel'));
            if ($(this).val() == 'yes') {
                rel.slideDown();
            } else {
                rel.slideUp();
                rel.find(":text,select").val("");
                rel.find(":radio,:checkbox").prop("checked", false);
            }
        });
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
@endsection