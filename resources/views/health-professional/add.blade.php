@extends('layouts.backend.app')
@section('content')
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
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Health Professional Information for Covid 19</h3>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        {!! rcForm::open('POST', route('health-professional.store'),['name' => 'create']) !!}
                        <div class="panel-body">
                            <h3>Organization Information</h3>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="control-label" for="organization_type">Organization Type</label>
                                    <select name="organization_type" class="form-control">
                                        <option value="" selected>Select any one</option>
                                        <option {{ $data['organization_type'] == '1' ? "selected" : "" }} {{ old('organization_type') == '1' ? "selected" : "" }} value="1">
                                            Government
                                        </option>
                                        <option  {{ $data['organization_type'] == '2' ? "selected" : "" }} {{ old('organization_type') == '2' ? "selected" : "" }} value="2">
                                            Non-profit
                                        </option>
                                        <option  {{ $data['organization_type'] == '3' ? "selected" : "" }} {{ old('organization_type') == '3' ? "selected" : "" }} value="3">
                                            Private
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group {{ $errors->has('organization_name') ? 'has-error' : '' }}  col-sm-6">
                                    <label for="organization_name">Organization Name</label>
                                    <input type="text" id="organization_name" class="form-control"
                                           value="{{ old('organization_name') ? old('organization_name') : $data['organization_name'] }}" name="organization_name"
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
                                           value="{{ old('organization_phn') ? old('organization_phn') : $data['organization_phn'] }}" name="organization_phn"
                                           aria-describedby="help" placeholder="Organization Phone No.">
                                    @if ($errors->has('organization_phn'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('organization_phn') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('organization_address') ? 'has-error' : '' }} col-sm-6">
                                    <label for="organization_address">Organization Address</label>
                                    <input type="text" id="organization_address" class="form-control"
                                           value="{{ old('organization_address') ? old('organization_address') : $data['organization_address'] }}" name="organization_address"
                                           aria-describedby="help" placeholder="Organization Address">
                                    @if ($errors->has('organization_address'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('organization_address') }}</small>
                                    @endif
                                </div>
                            </div>
                            <hr>
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
                            <div class="row">
                                <div class="form-group {{ $errors->has('service_date') ? 'has-error' : '' }} col-sm-6">
                                    <label for="service_date">Service Date[Y/MM]</label>
                                    <input type="text" id="service_date" class="form-control"
                                           value="{{ old('service_date') }}"
                                           name="service_date"
                                           aria-describedby="help"
                                           placeholder="eg. For 2 years 10 months, Enter : 2/10">
                                    @if ($errors->has('service_date'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('service_date') }}</small>
                                    @endif
                                </div>
                            </div>
                            <hr>
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
                                        <option value="" selected>Select any one</option>
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
                                        <select name="province_id" id="province_id" class="form-control"
                                                onchange="cProvinceOnchange($(this).val())">
                                            @foreach(App\Models\province::all() as $province)
                                                @if($province_id==$province->id || old('province_id')==$province->id)
                                                    @php($selectedProvince = "selected")
                                                @else
                                                    @php($selectedProvince = "")
                                                @endif
                                                <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province_id'))
                                            <small id="help"
                                                   class="form-text text-danger">{{ $errors->first('province_id') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group  col-sm-3" id="district">
                                        <select name="district_id" id="district_id" class="form-control"
                                                onchange="cDistrictOnchange($(this).val())">
                                            <option value=\"\">Select District</option>
                                            @foreach($districts as $district)
                                                @if($district_id==$district->id || old('district_id')==$district->id)
                                                    @php($selectedDistrict = "selected")
                                                @else
                                                    @php($selectedDistrict = "")
                                                @endif
                                                <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('district_id'))
                                            <small id="help"
                                                   class="form-text text-danger">{{ $errors->first('district_id') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group  col-sm-3" id="municipality">
                                        <select name="municipality_id" class="form-control"
                                                id="municipality_id">
                                            <option value=\"\">Select Municipality</option>
                                            @foreach($municipalities as $municipality)
                                                @if($municipality_id==$municipality->id  || old('municipality_id')==$municipality->id)
                                                    @php($selectedMunicipality = "selected")
                                                @else
                                                    @php($selectedMunicipality = "")
                                                @endif
                                                <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
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
                                           id="ward"
                                           aria-describedby="help" placeholder="Enter Ward No"
                                    >
                                    @if ($errors->has('ward'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }} col-sm-6">
                                    <label for="tole">Tole</label>
                                    <input type="text" class="form-control" value="{{ old('tole') }}" name="tole"
                                           id="tole"
                                           aria-describedby="help" placeholder="Enter Tole"
                                    >
                                    @if ($errors->has('tole'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="sameAsCheckbox" id="sameAsCheckbox"
                                       onclick="setSameAsCheckbox()">Same as Current Address<br>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Permanent Address</label>
                                <div class="row">
                                    <div class="form-group col-sm-3" id="perm_province">
                                        <select name="perm_province_id" id="perm_province_id" class="form-control"
                                                onchange="pProvinceOnchange($(this).val(), false)">
                                            @foreach(App\Models\province::all() as $province)
                                                @if($province_id==$province->id || old('perm_province_id')==$province->id)
                                                    @php($selectedPermProvince = "selected")
                                                @else
                                                    @php($selectedPermProvince = "")
                                                @endif
                                                <option value="{{$province->id ?? ''}}" {{$selectedPermProvince}}>{{$province->province_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('perm_province_id'))
                                            <small id="help"
                                                   class="form-text text-danger">{{ $errors->first('perm_province_id') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group  col-sm-3" id="perm_district">
                                        <select name="perm_district_id" id="perm_district_id" class="form-control"
                                                onchange="pDistrictOnchange($(this).val(),false)">
                                            <option value=\"\">Select District</option>
                                            @foreach($districts as $district)
                                                @if($district_id==$district->id || old('perm_district_id')==$district->id)
                                                    @php($selectedPermDistrict = "selected")
                                                @else
                                                    @php($selectedPermDistrict = "")
                                                @endif
                                                <option value="{{$district->id ?? ''}}" {{$selectedPermDistrict}}>{{$district->district_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('perm_district_id'))
                                            <small id="help"
                                                   class="form-text text-danger">{{ $errors->first('perm_district_id') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group  col-sm-3" id="perm_municipality">
                                        <select name="perm_municipality_id" class="form-control"
                                                id="perm_municipality_id">
                                            <option value=\"\">Select Municipality</option>
                                            @foreach($municipalities as $municipality)
                                                @if($municipality_id==$municipality->id  || old('perm_municipality_id')==$municipality->id)
                                                    @php($selectedPermMunicipality = "selected")
                                                @else
                                                    @php($selectedPermMunicipality = "")
                                                @endif
                                                <option value="{{$municipality->id ?? ''}}" {{$selectedPermMunicipality}}>{{$municipality->municipality_name}}</option>
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
                                    <input type="text" class="form-control" value="{{ old('perm_ward') }}"
                                           name="perm_ward" id="perm_ward"
                                           aria-describedby="help" placeholder="Enter Ward No"
                                    >
                                    @if ($errors->has('perm_ward'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('perm_ward') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('perm_tole') ? 'has-error' : '' }} col-sm-6">
                                    <label for="perm_tole">Tole</label>
                                    <input type="text" class="form-control" value="{{ old('perm_tole') }}"
                                           name="perm_tole" id="perm_tole"
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
                                    <label for="citizenship_no">Citizenship/Passport No/Organization No/License No</label>
                                    <input type="text" id="citizenship_no" class="form-control"
                                           value="{{ old('citizenship_no') }}" name="citizenship_no"
                                           aria-describedby="help" placeholder="Citizenship/Passport No./Organization No/License No">
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
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="control-label">Are you health worker?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="health_worker"
                                                   {{ old('health_worker') == "no" ? 'checked' : '' }} value="no"
                                                   onclick="toggleLayout(false)"
                                                   checked>No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio"
                                                   {{ old('health_worker') == "yes" ? 'checked' : '' }} name="health_worker"
                                                   value="yes" onclick="toggleLayout(true)">Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('council_no') ? 'has-error' : '' }} col-sm-6"
                                     id="council" hidden>
                                    <label for="council_no">Council No.</label>
                                    <input type="text" id="council_no" class="form-control"
                                           value="{{ old('council_no') }}"
                                           name="council_no"
                                           aria-describedby="help"
                                           placeholder="Enter your council no.">
                                    @if ($errors->has('council_no'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('council_no') }}</small>
                                    @endif
                                </div>
                            </div>
                            <hr>
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
                                    <label class="control-label">Are you covid infected?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="covid_status"
                                                   {{ old('covid_status') == "no" ? 'checked' : '' }} value="no"
                                                   checked>No
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
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script>
        function cProvinceOnchange(id) {
            $("#district").text("Loading...").fadeIn("slow");
            $.get("{{route("temp-district-select-province")}}?id=" + id, function (data) {
                $("#district").html(data);
            });
        }

        function pProvinceOnchange(id, select) {
            $("#perm_district").text("Loading...").fadeIn("slow");
            $.get("{{route("perm-district-select-province")}}?id=" + id, function (data) {
                $("#perm_district").html(data);
                if (select) {
                    var e = document.getElementById("district_id");
                    var selected = e.selectedIndex;
                    document.getElementById("perm_district_id").selectedIndex = selected;
                }
            });
        }

        function cDistrictOnchange(id) {
            $("#municipality_id").text("Loading...").fadeIn("slow");
            $.get("{{route("temp-municipality-select-district")}}?id=" + id, function (data) {
                $("#municipality_id").html(data);
            });
        }

        function pDistrictOnchange(id, select) {
            $("#perm_municipality_id").text("Loading...").fadeIn("slow");
            $.get("{{route("perm-municipality-select-district")}}?id=" + id, function (data) {
                $("#perm_municipality_id").html(data);
                if (select) {
                    var e = document.getElementById("municipality_id");
                    var selected = e.selectedIndex;
                    document.getElementById("perm_municipality_id").selectedIndex = selected;
                }
            });
        }

        function verifyInfo() {
            $("#verifydiv").dialog({
                autoOpen: false
            });
            $("#verifydiv").dialog('open');
        }

        // function sameAsCheckbox() {
        //     ("#sameAsCheckbox").click(function () {
        //         if ($(this).is(':checked')) {
        //             var input1 = $("#province_id").val();
        //             $("#prem_province_id").val(input1);
        //         }
        //     });
        // }

        function setSameAsCheckbox() {
            if ($("#sameAsCheckbox").is(":checked")) {
                var inputProvince = $("#province_id").val();
                var inputDistrict = $("#district_id").val();
                var ward = document.getElementById("ward").value;
                var tole = document.getElementById("tole").value;
                document.getElementById("perm_ward").value = ward;
                document.getElementById("perm_tole").value = tole;
                var e = document.getElementById("province_id");
                var selected = e.selectedIndex;
                document.getElementById("perm_province_id").selectedIndex = selected;
                pProvinceOnchange(inputProvince, true);
                pDistrictOnchange(inputDistrict, true);
                // $("#prem_province_id").val($("#province_id").val());
                // // $('#perm_municipality').val($('#municipality').val());
                // $('#prem_province_id').attr('disabled', 'disabled');
                // // $('#perm_municipality').attr('disabled', 'disabled');
            } else {
                // console.log('not checked');
            }
        }

        function toggleLayout(healthWorker) {
            x = document.getElementById("council");
            if (healthWorker) {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        $(':radio[data-rel]').change(function () {
            var rel = $("." + $(this).data('rel'));
            if ($(this).val() === 'yes') {
                rel.slideDown();
            } else {
                rel.slideUp();
                rel.find(":text,select").val("");
                rel.find(":radio,:checkbox").prop("checked", false);
            }
        });
        $(function () {
            $.validator.addMethod("emailCustom", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
            }, "Email Address is invalid: Please enter a valid email address.");

            $.validator.addMethod("ageCustom", function (value, element) {
                return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
            }, "Age is invalid: Please enter a valid age.");

            $.validator.addMethod("phoneCustom", function (value, element) {
                return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
            }, "Contact number is invalid: Please enter a valid phone number.");

            $("form[name='create']").validate({
                // Define validation rules
                rules: {
                    organization_type: {
                        required: true,
                    },
                    organization_name: {
                        required: true,
                    },
                    organization_phn: {
                        required: true,
                    },
                    organization_address: {
                        required: true,
                    },
                    designation: {
                        required: true,
                    },
                    service_date: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    gender: {
                        required: true,
                    },
                    age: {
                        required: true,
                        // ageCustom: true,
                        min: 15,
                        max: 125
                    },
                    phone: {
                        required: true,
                        phoneCustom: true
                    },
                    district_id: {
                        required: true
                    },
                    municipality_id: {
                        required: true
                    },
                    ward: {
                        required: true,
                    },
                    tole: {
                        required: true,
                    },
                    perm_ward: {
                        required: true,
                    },
                    perm_tole: {
                        required: true,
                    },
                    occupation: {
                        required: true,
                    }
                },
                // Specify validation error messages
                messages: {
                    name: "Please provide a valid name.",
                    age: "Please provide a valid age.",

                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection