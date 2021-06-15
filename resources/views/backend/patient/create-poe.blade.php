@extends('layouts.backend.app')
@section('style')
    <style>
        .earning {
            display: none;
        }

        form {
            background: #ecf5fc;
            padding: 20px 50px 45px;
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
@endsection
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                       <h3 class="text-center"><span id="form_title"></span> Registration Form</h3>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('woman.store'), ['name' => 'createCase']) !!}
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label"><h3>Test Type</h3></label>
                                <div class="control-group">
                                    <label class="radio-inline" style="padding-right: 60px;">
                                        <h4>PCR Swab Collection</h4>
                                        <input type="radio" name="service_for" value="1" onclick="toggleLayout(true)" required style="top: 7px;">
                                    </label>
                                    <label class="radio-inline">
                                        <h4>Antigen Test</h4>
                                        <input type="radio" name="service_for" value="2" onclick="toggleLayout(false)" style="top: 7px;">
                                    </label>
                                </div>
                                @if ($errors->has('service_for'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('service_for') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control" value="{{ old('name') }}" name="name"
                                       aria-describedby="help" placeholder="Enter Full Name">
                                @if ($errors->has('name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                <label for="age">Age</label>
                                <input type="text" id="age" value="{{ old('age') }}" class="form-control col-xs-9"
                                       name="age" placeholder="Enter Age"
                                ><br>
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                       checked>Years
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                                <br>
                                @if ($errors->has('age'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="caste">Ethnicity</label>
                                <select name="caste" class="form-control">
                                    <option {{ old('caste') == '7' ? "selected" : "" }} value="7">Don't Know</option>
                                    <option {{ old('caste') == '1' ? "selected" : "" }} value="1">Dalit</option>
                                    <option {{ old('caste') == '2' ? "selected" : "" }} value="2">Janajati</option>
                                    <option {{ old('caste') == '3' ? "selected" : "" }} value="3">Madheshi</option>
                                    <option {{ old('caste') == '4' ? "selected" : "" }} value="4">Muslim</option>
                                    <option {{ old('caste') == '5' ? "selected" : "" }} value="5">Brahmin/Chhetrai
                                    </option>
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
                                <label class="control-label" for="occupation">Occupation</label>
                                <select name="occupation" class="form-control">
                                    <option {{ old('occupation') == '' ? "selected" : "" }} value="">Select Occupation
                                    </option>
                                    <option {{ old('occupation') == '1' ? "selected" : "" }} value="1">Front Line Health
                                        Worker
                                    </option>
                                    <option {{ old('occupation') == '2' ? "selected" : "" }} value="2">Doctor</option>
                                    <option {{ old('occupation') == '3' ? "selected" : "" }} value="3">Nurse</option>
                                    <option {{ old('occupation') == '4' ? "selected" : "" }} value="4">Police/Army
                                    </option>
                                    <option {{ old('occupation') == '5' ? "selected" : "" }} value="5">
                                        Business/Industry
                                    </option>
                                    <option {{ old('occupation') == '6' ? "selected" : "" }} value="6">
                                        Teacher/Student/Education
                                    </option>
                                    <option {{ old('occupation') == '7' ? "selected" : "" }} value="7">Journalist
                                    </option>
                                    <option {{ old('occupation') == '8' ? "selected" : "" }} value="8">Agriculture
                                    </option>
                                    <option {{ old('occupation') == '9' ? "selected" : "" }} value="9">
                                        Transport/Delivery
                                    </option>
                                    <option {{ old('occupation') == '10' ? "selected" : "" }} value="10">Other</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('nationality') ? 'has-error' : '' }}">
                                <label for="nationality">Nationality</label>
                                <input type="text" id="nationality" class="form-control" value="{{ old('nationality') }}" name="nationality"
                                       aria-describedby="help" placeholder="Enter Nationality">
                                @if ($errors->has('nationality'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('nationality') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('id_card_detail') ? 'has-error' : '' }}">
                                <label for="id_card_detail">Passport/Nationality No</label>
                                <input type="text" id="id_card_detail" class="form-control" value="{{ old('id_card_detail') }}" name="id_card_detail"
                                       aria-describedby="help" placeholder="Enter Passport/Nationality No">
                                @if ($errors->has('id_card_detail'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('id_card_detail') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('travelled_where') ? 'has-error' : '' }}">
                                <label for="travelled_where">Travel From</label>
                                <input type="text" id="travelled_where" class="form-control" value="{{ old('travelled_where') }}" name="travelled_where"
                                       aria-describedby="help" placeholder="Enter Travel From">
                                @if ($errors->has('travelled_where'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('travelled_where') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('travelled_date') ? 'has-error' : '' }}">
                                <label for="travelled_date">Travel Date</label>
                                <input type="text" id="travelled_date" class="form-control" value="{{ old('travelled_date') }}" name="travelled_date"
                                       aria-describedby="help" placeholder="Enter Travel Date">
                                @if ($errors->has('travelled_date'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('travelled_date') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Destination in Nepal</label>
                                <div class="row">
                                    <div class="form-group col-sm-3" id="province">
                                        <select name="province_id" class="form-control"
                                                onchange="provinceOnchange($(this).val())">
                                            @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                <option value="">Select All Provinces</option>
                                            @endif
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
                                        <select name="district_id" class="form-control"
                                                onchange="districtOnchange($(this).val())">
                                            @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                <option value="">Select All Districts</option>
                                            @endif
                                            @foreach(App\Models\District::where('province_id', $province_id)->get() as $district)
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
                                                onchange="municipalityOnchange($(this).val())"
                                                id="municipality_id">
                                            @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                <option value="">Select All Municipalities</option>
                                            @endif
                                            @foreach(\App\Models\Municipality::where('district_id', $district_id)->get() as $municipality)
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
                            <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                                <label for="ward">Destination in Nepal (Ward No)</label>
                                <input type="text" class="form-control" value="{{ old('ward') }}" name="ward"
                                       aria-describedby="help" placeholder="Enter Ward No"
                                >
                                @if ($errors->has('ward'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                                <label for="tole">Destination in Nepal (Tole)</label>
                                <input type="text" class="form-control" value="{{ old('tole') }}" name="tole"
                                       aria-describedby="help" placeholder="Enter Tole"
                                >
                                @if ($errors->has('tole'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                <label for="name">Contact phone number in Nepal</label>
                                <input type="text" class="form-control" value="{{ old('emergency_contact_one') }}"
                                       name="emergency_contact_one" aria-describedby="help"
                                       placeholder="Contact phone number in Nepal"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('nearest_contact') ? 'has-error' : '' }}">
                                <label for="name">Nearest Contact person in Nepal</label>
                                <input type="text" class="form-control" value="{{ old('nearest_contact') }}"
                                       name="nearest_contact" aria-describedby="help"
                                       placeholder="Nearest Contact person in Nepal"
                                >
                                @if ($errors->has('nearest_contact'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('nearest_contact') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                <label for="name">Nearest Contact person Phone</label>
                                <input type="text" class="form-control" value="{{ old('emergency_contact_two') }}"
                                       name="emergency_contact_two" aria-describedby="help"
                                       placeholder="Enter Nearest Contact person Phone">
                                @if ($errors->has('emergency_contact_two'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_two') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Currently symptomatic</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="symptoms_recent"
                                               {{ old('symptoms_recent') == "0" ? 'checked' : '' }} value="0"
                                               data-rel="earning" checked onclick="toggleDateOnset(true)">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('symptoms_recent') == "1" ? 'checked' : '' }} name="symptoms_recent"
                                               value="1" data-rel="earning" onclick="toggleDateOnset(false)">Yes
                                    </label>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('temperature') ? 'has-error' : '' }}">
                                <label for="name">Body Temperature</label>
                                <input type="number" class="form-control" value="{{ old('temperature') }}"
                                       name="temperature" aria-describedby="help"
                                       placeholder="Enter Body Temperature">
                                @if ($errors->has('temperature'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('temperature') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Have any symptoms of Covid-19 seen anytime during the past 2 weeks?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="symptoms_within_four_week"
                                               {{ old('symptoms_within_four_week') == "0" ? 'checked' : '' }} value="0"
                                               data-rel="earning" checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('symptoms_within_four_week') == "1" ? 'checked' : '' }} name="symptoms_within_four_week"
                                               value="1" data-rel="earning">Yes
                                    </label>
                                </div>
                            </div>
                            <div class="is-symptomatic" style="display: none;">
                                <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom') ? 'has-error' : '' }}">
                                    <label for="date_of_onset_of_first_symptom">Date of onset of first symptom:</label>
                                    <input type="text" class="form-control" value="{{ date('Y-m-d') }}"
                                        name="date_of_onset_of_first_symptom" aria-describedby="help">
                                    @if ($errors->has('date_of_onset_of_first_symptom'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom') }}</small>
                                    @endif
                                </div>
                                <div class="form-group" id="symptomatic-patient">
                                    <label class="control-label" for="symptoms">Symptomatic patient with:</label><br>
                                    <input type="checkbox" name="symptoms[]" value="1">Pneumonia<br>
                                    <input type="checkbox" name="symptoms[]" value="2">ARDS<br>
                                    <input type="checkbox" name="symptoms[]" value="3">Influenza-like illness<br>
                                    <input type="checkbox" name="symptoms[]" value="4">History of fever/chills<br>
                                    <input type="checkbox" name="symptoms[]" value="5">General weakness<br>
                                    <input type="checkbox" name="symptoms[]" value="6">Cough<br>
                                    <input type="checkbox" name="symptoms[]" value="7">Sore Throat<br>
                                    <input type="checkbox" name="symptoms[]" value="8">Running nose<br>
                                    <input type="checkbox" name="symptoms[]" value="9">Shortness of breath<br>
                                    <input type="checkbox" name="symptoms[]" value="10">Irritability/Confusion<br>
                                    <input type="checkbox" name="symptoms[]" value="11">Loss of taste<br>
                                    <input type="checkbox" name="symptoms[]" value="12">Loss of smell<br>
                                    <div style="padding: 10px;">
                                        <label>Pain</label><br>
                                        <input type="checkbox" name="symptoms[]" value="13">Muscular<br>
                                        <input type="checkbox" name="symptoms[]" value="14">Chest<br>
                                        <input type="checkbox" name="symptoms[]" value="15">Abdominal<br>
                                        <input type="checkbox" name="symptoms[]" value="16">Joint<br>
                                    </div>
                                    <input type="checkbox" name="symptoms[]" value="17">Diarrhea<br>
                                    <input type="checkbox" name="symptoms[]" value="18">Nausea/vomiting<br>
                                    <input type="checkbox" name="symptoms[]" value="19">Headache<br>
                                    <input type="checkbox" name="symptoms[]" value="20">Pharyngeal exudate<br>
                                    <input type="checkbox" name="symptoms[]" value="21">Conjunctival injection(eye)<br>
                                    <input type="checkbox" name="symptoms[]" value="22">Seizure<br>
                                    <input type="checkbox" name="symptoms[]" value="23">Coma<br>
                                    <input type="checkbox" name="symptoms[]" value="24">Dyspnea/tachynea(DB/Fast breathing)<br>
                                    <input type="checkbox" name="symptoms[]" value="25">Abnormal lung auscultation<br>
                                    <input type="checkbox" name="symptoms[]" value="26">Abnormal lung x-ray/CT scan findings<br>
                                    @if ($errors->has('symptoms'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('symptoms') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('symptoms_specific') ? 'has-error' : '' }}">
                                    <label for="symptoms_specific">If other specify</label>
                                    <input type="text" class="form-control" value="{{ old('symptoms_specific') }}" name="symptoms_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_specific') }}</small>
                                    @endif
                                </div>
                                <div class="form-group" id="symptoms_comorbidity">
                                    <label class="control-label" for="symptoms_comorbidity">Symptomatic patient with comorbidity</label><br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="1">Diabetes<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="2">HTN<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="3">Hermodialysis<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="4">Immunocompromised<br>
                                    <div style="padding: 10px;">
                                        <label>Pregnancy(Trimester)</label><br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="5">First<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="16">Second<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="17">Third<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="">No<br>
                                    </div>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="6">Maternity<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="7">Heart disease, including hypertension<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="8">Liver disease<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="9">Nerve related diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="10">Kidney diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="11">Malnutrition<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="12">Autoimmune diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="13">Immunodeficiency, including HIV<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="14">Malignancy<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="15">Chric lung disesase/asthma/artery<br>
                                    @if ($errors->has('symptoms_comorbidity'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('symptoms_comorbidity') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('symptoms_comorbidity_specific') ? 'has-error' : '' }}">
                                    <label for="symptoms_comorbidity_specific">If other specify</label>
                                    <input type="text" class="form-control" value="{{ old('symptoms_comorbidity_specific') }}" name="symptoms_comorbidity_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_comorbidity_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Is Detected From Contract Tracing ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_detected"
                                               {{ old('is_detected') == "no" ? 'checked' : '' }} value="no"
                                               data-rel="earning" checked onclick="toggleReasonLayout(true)">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('is_detected') == "yes" ? 'checked' : '' }} name="is_detected"
                                               value="yes" data-rel="earning" onclick="toggleReasonLayout(false)">Yes
                                    </label>
                                    <div class="earning form-group">
                                        <label class="form-label">Parent Case ID</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="{{ old('parent_case_id') }}"
                                                   name="parent_case_id" placeholder="Enter Parent Case ID"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Have you traveled anywhere till 14 days ago?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('travelled') == "0" ? 'checked' : '' }} name="travelled" value="0"
                                               checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('travelled') == "1" ? 'checked' : '' }} name="travelled"
                                               value="1">Yes
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="case_reason">
                                <label class="control-label" for="reson_for_testing">Reason for testing:</label><br>
                                <input type="checkbox" name="reson_for_testing[]" value="1">Planned travel<br>
                                <input type="checkbox" name="reson_for_testing[]" value="2">Mandatory requirement<br>
                                <input type="checkbox" name="reson_for_testing[]" value="3">Returnee/Migrant worker<br>
                                <input type="checkbox" name="reson_for_testing[]" value="4">Pre-medical/surgical procedure<br>
                                <input type="checkbox" name="reson_for_testing[]" value="5">Pregnancy complications/Pre-delivery<br>
                                <input type="checkbox" name="reson_for_testing[]" value="6">Testing by Government authority for other purpose<br>
                                <input type="checkbox" name="reson_for_testing[]" value="7">Test on demand by person<br>
                                @if ($errors->has('reson_for_testing'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('reson_for_testing') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Are you collecting COVID -19 swab now ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('swab_collection_conformation') == "0" ? 'checked' : '' }} name="swab_collection_conformation"
                                               value="0" class="swab_collection_conformation">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('swab_collection_conformation') == "1" ? 'checked' : '' }} name="swab_collection_conformation"
                                               value="1" checked class="swab_collection_conformation">Yes
                                    </label>

                                </div>
                            </div>

                            <div class="swab-data">
                            
                                <div id="sample">
                                    <div class="form-group">
                                        <label class="control-label">Sample Collection Type</label>
                                        <div class="control-group">
                                            <input type="checkbox" name="sample_type[]" value="1"> Nasopharyngeal<br>
                                            <input type="checkbox" name="sample_type[]" value="2"> Oropharyngeal
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('sample_type_specific') ? 'has-error' : '' }} ">
                                        <label for="sample_type_specific">If other specify sample collected type</label>
                                        <input type="text" class="form-control" name="sample_type_specific"
                                               aria-describedby="help"
                                               placeholder="Enter if other specify sample collected type"
                                        >
                                        @if ($errors->has('sample_type_specific'))
                                            <small id="help"
                                                   class="form-text text-danger">{{ $errors->first('sample_type_specific') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Infection Type</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="infection_type" value="1">Symptomatic
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="infection_type" value="2">Asymptomatic
                                        </label>
                                    </div>
                                    @if ($errors->has('infection_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('infection_type') }}</small>
                                    @endif
                                </div>
    
                                <div class="form-group">
                                    <label class="control-label">Service Type</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="service_type" value="1">Paid Service
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="service_type" value="2">Free of cost service
                                        </label>
                                    </div>
                                    @if ($errors->has('service_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('service_type') }}</small>
                                    @endif
                                </div>
                                <div class="panel panel-danger">
                                    <div class="panel-heading"><strong>Auto Generated Sample ID is :</strong></div>
                                    <?php
                                        $id = App\Models\OrganizationMember::where('token', auth()->user()->token)->first()->id;
                                        $time = explode(':', Carbon\Carbon::now()->format('H:i:s'));
                                        $converted_time = ($time[0] * 3600) + ($time[1] * 60) + $time[2];
                                        $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon\Carbon::now()->format('ymd') . '-' . $converted_time;
                                    ?>
                                    <div class="panel-body text-center"><h3>{{ $swab_id }}</h3></div>
                                </div>
                                <input type="text" name="token" value="{{$swab_id}}" hidden>
                                {{-- <input type="text" name="woman_token" value="{{$token}}" hidden> --}}
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
    </div>
    <!-- /#page-wrapper -->
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $(':radio[data-rel]').change(function () {
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

        function toggleReasonLayout(reason) {
            x = document.getElementById("reason");
            if (reason) {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function toggleDateOnset(val) {
            if (val) {
                $('.is-symptomatic').hide();
            } else {
                $('.is-symptomatic').show();
            }            
        }

        function toggleLayout(sample) {
            x = document.getElementById("sample");
            if (sample) {
                x.style.display = "block";
                $('#form_title').html('PCR');
            } else {
                x.style.display = "none";
                $('#form_title').html('Antigen');
            }
        }

        swabFormShow();
        $('.swab_collection_conformation').on('change', function() {
        console.log('ss');
            swabFormShow();
        });
        function swabFormShow() {
            if($('.swab_collection_conformation:checked').val() == '1'){
                $('.swab-data').show();
            }
            else {
                $('.swab-data').hide();
            }
        }
        

        $(function () {
            $.validator.addMethod("nameCustom", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
            }, "Email Address is invalid: Please enter a valid email address.");

            $.validator.addMethod("ageCustom", function (value, element) {
                return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
            }, "Age is invalid: Please enter a valid age.");

            $.validator.addMethod("phoneCustom", function (value, element) {
                return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
            }, "Contact number is invalid: Please enter a valid phone number.");
            $("form[name='createCase']").validate({
                // Define validation rules
                rules: {
                    name: {
                        required: true,
                        nameCustom: true
                    },
                    age: {
                        required: true,
                        ageCustom: true,
                    },
                    district_id: {
                        required: true
                    },
                    municipality_id: {
                        required: true
                    },
                    sex: {
                        required: true,
                    },
                    ward: {
                        required: true,
                    },
                    tole: {
                        required: true,
                    },
                    emergency_contact_one: {
                        required: true,
                        phoneCustom: true
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