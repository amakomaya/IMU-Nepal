<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}"
          rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('bower_components/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <style>
        .earning {
            display: none;
        }

        form {
            background: #ecf5fc;
            padding: 40px 50px 45px;
        }

        .form-control:focus {
            border-color: #000;
            box-shadow: none;
        }

        label {
            font-weight: 600;
        }

        .error {
            color: red;
            font-weight: 400;
            display: block;
            padding: 6px 0;
            font-size: 14px;
        }

        .form-control.error {
            border-color: red;
            padding: .375rem .75rem;
        }
    </style>
</head>
<body>
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Health Professional</h3>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    {!! rcForm::open('POST', route('health-professional.store')) !!}
                    <div class="panel-body">
                        <h3>Organization Information</h3>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label class="control-label" for="organization_type">Organization Type</label>
                                <select name="organization_type" class="form-control">
                                    <option selected>Select any one</option>
                                    <option {{ old('organization_type') == '1' ? "selected" : "" }} value="1">Government
                                    </option>
                                    <option {{ old('organization_type') == '2' ? "selected" : "" }} value="2">Non-profit
                                    </option>
                                    <option {{ old('organization_type') == '3' ? "selected" : "" }} value="3">Private
                                    </option>
                                </select>
                            </div>
                            <div class="form-group {{ $errors->has('organization_name') ? 'has-error' : '' }}  col-sm-6">
                                <label for="organization_name">Organization Name</label>
                                <input type="text" id="organization_name" class="form-control"
                                       value="{{ old('organization_name') }}" name="organization_name"
                                       aria-describedby="help" placeholder="Organization Name">
                                @if ($errors->has('organization_name'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('organization_name') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('organization_phn') ? 'has-error' : '' }} col-sm-6">
                                <label for="organization_phn">Organization Phone No.</label>
                                <input type="text" id="organization_phn" class="form-control"
                                       value="{{ old('organization_phn') }}" name="organization_phn"
                                       aria-describedby="help" placeholder="Organization Phone No.">
                                @if ($errors->has('organization_phn'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('organization_phn') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('organization_address') ? 'has-error' : '' }} col-sm-6">
                                <label for="organization_address">Organization Address</label>
                                <input type="text" id="organization_address" class="form-control"
                                       value="{{ old('organization_address') }}" name="organization_address"
                                       aria-describedby="help" placeholder="Organization Address">
                                @if ($errors->has('organization_address'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('organization_address') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }} col-sm-6">
                                <label for="designation">Designation(Post)</label>
                                <input type="text" id="designation" class="form-control"
                                       value="{{ old('designation') }}" name="designation"
                                       aria-describedby="help" placeholder="Organization Name">
                                @if ($errors->has('designation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('designation') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }} col-sm-6">
                                <label for="level">Level</label>
                                <input type="text" id="level" class="form-control"
                                       value="{{ old('level') }}" name="level"
                                       aria-describedby="help" placeholder="Level">
                                @if ($errors->has('level'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('level') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" id="app">
                            <select-year-month></select-year-month>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('service_date') ? 'has-error' : '' }} col-sm-6">
                                <label for="service_date">Service Date[YYYY-MM-DD]</label>
                                <input type="text" id="service_date" class="form-control"
                                       value="{{ old('service_date') ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                       name="service_date"
                                       aria-describedby="help" placeholder="YYYY-MM-DD">
                                @if ($errors->has('service_date'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('service_date') }}</small>
                                @endif
                            </div>
                        </div>
                        <h3>Personal Information</h3>
                        <div class="row">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-sm-6">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control"
                                       value="{{ old('name') }}" name="name"
                                       aria-describedby="help" placeholder="Full Name">
                                @if ($errors->has('name'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="control-label" for="gender">Gender</label>
                                <select name="gender" class="form-control">
                                    <option selected>Select any one</option>
                                    <option {{ old('gender') == '1' ? "selected" : "" }} value="1">Male
                                    </option>
                                    <option {{ old('gender') == '2' ? "selected" : "" }} value="2">Female
                                    </option>
                                    <option {{ old('gender') == '3' ? "selected" : "" }} value="3">Other
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }} col-sm-6">
                                <label for="age">Age in year</label>
                                <input type="text" id="age" class="form-control"
                                       value="{{ old('age') }}" name="age"
                                       aria-describedby="help" placeholder="Age">
                                @if ($errors->has('age'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('age') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }} col-sm-6">
                                <label for="phone">Contact Number(Phone)</label>
                                <input type="text" id="phone" class="form-control"
                                       value="{{ old('phone') }}" name="phone"
                                       aria-describedby="help" placeholder="Contact Number">
                                @if ($errors->has('phone'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('phone') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Current Address</label>
                            <div class="row">
                                <div class="form-group col-sm-3" id="province">
                                    <select name="province_id" class="form-control"
                                            onchange="provinceOnchange($(this).val())">
                                        @foreach(App\Models\province::all() as $province)
                                            @if($province_id==$province->id || old('province_id')==$province->id)
                                                @php($selectedProvince = "selected")
                                            @else
                                                @php($selectedProvince = "")
                                            @endif
                                            <option value="{{$province->id ?? ''}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('province_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('province_id') }}</small>
                                    @endif
                                </div>
                                <div class="form-group  col-sm-3" id="district">
                                    <select name="district_id" class="form-control"
                                            onchange="districtOnchange($(this).val())">
                                        @foreach(App\Models\District::where('province_id', $province_id ?? '')->get() as $district)
                                            @if($district_id==$district->id || old('district_id')==$district->id)
                                                @php($selectedDistrict = "selected")
                                            @else
                                                @php($selectedDistrict = "")
                                            @endif
                                            <option value="{{$district->id ?? ''}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('district_id') }}</small>
                                    @endif
                                </div>
                                <div class="form-group  col-sm-3" id="municipality">
                                    <select name="municipality_id" class="form-control"
                                            onchange="municipalityOnchange($(this).val())"
                                            id="municipality_id">
                                        @foreach(\App\Models\Municipality::where('district_id', $district_id ?? '')->get() as $municipality)
                                            @if($municipality_id==$municipality->id  || old('municipality_id')==$municipality->id)
                                                @php($selectedMunicipality = "selected")
                                            @else
                                                @php($selectedMunicipality = "")
                                            @endif
                                            <option value="{{$municipality->id ?? ''}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('municipality_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('municipality_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }} col-sm-6">
                                <label for="ward">Ward No</label>
                                <input type="text" class="form-control" value="{{ old('ward') }}" name="ward"
                                       aria-describedby="help" placeholder="Enter Ward No"
                                >
                                @if ($errors->has('ward'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }} col-sm-6">
                                <label for="tole">Tole</label>
                                <input type="text" class="form-control" value="{{ old('tole') }}" name="tole"
                                       aria-describedby="help" placeholder="Enter Tole"
                                >
                                @if ($errors->has('tole'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Permanent Address</label>
                            <div class="row">
                                <div class="form-group col-sm-3" id="perm_province">
                                    <select name="perm_province_id" class="form-control"
                                            onchange="provinceOnchange($(this).val())">
                                        @foreach(App\Models\province::all() as $province)
                                            @if($province_id==$province->id || old('perm_province_id')==$province->id)
                                                @php($selectedProvince = "selected")
                                            @else
                                                @php($selectedProvince = "")
                                            @endif
                                            <option value="{{$province->id ?? ''}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('perm_province_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('perm_province_id') }}</small>
                                    @endif
                                </div>
                                <div class="form-group  col-sm-3" id="perm_district">
                                    <select name="perm_district_id" class="form-control"
                                            onchange="districtOnchange($(this).val())">
                                        @foreach(App\Models\District::where('province_id', $province_id ?? '')->get() as $district)
                                            @if($district_id==$district->id || old('perm_district_id')==$district->id)
                                                @php($selectedDistrict = "selected")
                                            @else
                                                @php($selectedDistrict = "")
                                            @endif
                                            <option value="{{$district->id ?? ''}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('perm_district_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('perm_district_id') }}</small>
                                    @endif
                                </div>
                                <div class="form-group  col-sm-3" id="perm_municipality">
                                    <select name="perm_municipality_id" class="form-control"
                                            onchange="municipalityOnchange($(this).val())"
                                            id="perm_municipality_id">
                                        @foreach(\App\Models\Municipality::where('district_id', $district_id ?? '')->get() as $municipality)
                                            @if($municipality_id==$municipality->id  || old('perm_municipality_id')==$municipality->id)
                                                @php($selectedMunicipality = "selected")
                                            @else
                                                @php($selectedMunicipality = "")
                                            @endif
                                            <option value="{{$municipality->id ?? ''}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('perm_municipality_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('perm_municipality_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('perm_ward') ? 'has-error' : '' }} col-sm-6">
                                <label for="perm_ward">Ward No</label>
                                <input type="text" class="form-control" value="{{ old('perm_ward') }}" name="perm_ward"
                                       aria-describedby="help" placeholder="Enter Ward No"
                                >
                                @if ($errors->has('perm_ward'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('perm_ward') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('perm_tole') ? 'has-error' : '' }} col-sm-6">
                                <label for="perm_tole">Tole</label>
                                <input type="text" class="form-control" value="{{ old('perm_tole') }}" name="perm_tole"
                                       aria-describedby="help" placeholder="Enter Tole"
                                >
                                @if ($errors->has('perm_tole'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('perm_tole') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->has('citizenship_no') ? 'has-error' : '' }} col-sm-6">
                                <label for="citizenship_no">Citizenship/Password No.</label>
                                <input type="text" id="citizenship_no" class="form-control"
                                       value="{{ old('citizenship_no') }}" name="citizenship_no"
                                       aria-describedby="help" placeholder="Citizenship/Password No.">
                                @if ($errors->has('citizenship_no'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('citizenship_no') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('issue_district') ? 'has-error' : '' }} col-sm-6">
                                <label for="issue_district">Issue authority/District</label>
                                <input type="text" id="issue_district" class="form-control"
                                       value="{{ old('issue_district') }}" name="issue_district"
                                       aria-describedby="help" placeholder="Issue authority/District">
                                @if ($errors->has('issue_district'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('issue_district') }}</small>
                                @endif
                            </div>
                        </div>
                        <h3>Health Related Information</h3>
                        <div class="row">
                            <div class="form-group {{ $errors->has('allergies') ? 'has-error' : '' }} col-sm-6">
                                <label for="allergies">Allergies</label>
                                <input type="text" id="allergies" class="form-control"
                                       value="{{ old('allergies') }}" name="allergies"
                                       aria-describedby="help" placeholder="Allergies">
                                @if ($errors->has('allergies'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('allergies') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="control-label">COVID status</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="covid_status"
                                               {{ old('covid_status') == "no" ? 'checked' : '' }} value="no" checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('covid_status') == "yes" ? 'checked' : '' }} name="covid_status"
                                               value="yes">Yes
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('disease') ? 'has-error' : '' }} col-sm-6">
                            <label for="disease">Health/disease</label><br>
                            <input type="checkbox" name="disease[]" value="1">DIABETES<br>
                            <input type="checkbox" name="disease[]" value="2">HTN<br>
                            <input type="checkbox" name="disease[]" value="3">HERMODIALYSIS<br>
                            <input type="checkbox" name="disease[]" value="4">IMMUNOCOMPROMISED <br>
                            <input type="checkbox" name="disease[]" value="5">PREGNANCY <br>
                            <input type="checkbox" name="disease[]" value="6">MATERNITY <br>
                            <input type="checkbox" name="disease[]" value="7">HEART_DISEASE <br>
                            <input type="checkbox" name="disease[]" value="8">LIVER_DISEASE <br>
                            <input type="checkbox" name="disease[]" value="9">NERVE_DISEASE <br>
                            <input type="checkbox" name="disease[]" value="10">KIDNEY_DISEASE <br>
                            <input type="checkbox" name="disease[]" value="11">MALNUTRITION <br>
                            <input type="checkbox" name="disease[]" value="12">AUTO_IMMUNE_DISEASE <br>
                            <input type="checkbox" name="disease[]" value="13">IMMUNODEFICIENCY <br>
                            <input type="checkbox" name="disease[]" value="14">MALIGNNACY <br>
                            <input type="checkbox" name="disease[]" value="15">CHORNIC_LUNG_ILLNESS <br>
                        </div>
                        {!! rcForm::close('post') !!}
                        {{--                        <button class="btn btn-primary btn-lg btn-block" title="Submit" onclick="verifyInfo()">--}}
                        {{--                            Submit--}}
                        {{--                        </button>--}}
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
</div>
<div id="verifydiv" hidden>
    <div class="form-group">
        <h4><u><strong>Please verify your information</strong></u></h4>
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Organization Type:</td>
                <td></td>
            </tr>
            <tr>
                <td>Organization Name:</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
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

    function verifyInfo() {
        $( "#verifydiv" ).dialog({
            autoOpen: false
        });
        $("#verifydiv").dialog('open');
    }
</script>
</body>
</html>