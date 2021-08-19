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
                        <h3 class="text-center">
                            <span id="form_title"></span> Registration Form
                        </h3>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form class="form-group" role="form" action="{{route('woman.store')}}" enctype="multipart/form-data" method="POST" name="createCase" id="createCase" novalidate="novalidate" onsubmit="disableSubmit()">
                          @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label"><h3>Test Type</h3></label>
                                <div class="control-group">
                                    <label class="radio-inline" style="padding-right: 60px;">
                                        <h4>PCR Swab Collection</h4>
                                        <input type="radio" name="service_for" value="1" class="service_for" onclick="toggleLayout(true)" required style="top: 7px;">
                                    </label>
                                    <label class="radio-inline">
                                        <h4>Antigen Test</h4>
                                        <input type="radio" name="service_for" value="2" class="service_for" onclick="toggleLayout(false)" style="top: 7px;">
                                    </label>
                                </div>
                                @if ($errors->has('service_for'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('service_for') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control" value="{{ $data->name ?? '' }}" name="name"
                                       aria-describedby="help" placeholder="Enter Full Name">
                                @if ($errors->has('name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                <label for="age">Age</label>
                                <input type="text" id="age" value="{{ $data->age ?? '' }}" class="form-control col-xs-9"
                                       name="age" placeholder="Enter Age"
                                ><br>
                                <input type="radio" name="age_unit"
                                       {{ $data->age_unit == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                       checked>Years
                                <input type="radio" name="age_unit"
                                       {{ $data->age_unit == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                                <input type="radio" name="age_unit"
                                       {{ $data->age_unit == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
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
                                    <option {{ old('caste') == '5' ? "selected" : "" }} value="5">Brahmin/Chhetri
                                    </option>
                                    <option {{ old('caste') == '6' ? "selected" : "" }} value="6">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Gender</label>
                                <select name="sex" class="form-control">
                                    <option {{ $data->sex == '' ? "selected" : "" }} value="">Select Gender</option>
                                    <option {{ $data->sex == '1' ? "selected" : "" }} value="1">Male</option>
                                    <option {{ $data->sex == '2' ? "selected" : "" }} value="2">Female</option>
                                    <option {{ $data->sex == '3' ? "selected" : "" }}  value="3">Other</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Current Address</label>
                                <div class="row">
                                    <div class="form-group col-sm-3" id="province">
                                        <select name="province_id" class="form-control"
                                                onchange="provinceOnchange($(this).val())">
                                            @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                <option value="">Select All Provinces</option>
                                            @endif
                                            @foreach(\Illuminate\Support\Facades\Cache::remember('province-list', 48*60*60, function () {
                                              return App\Models\Province::select(['id', 'province_name'])->get();
                                            }) as $province)
                                                @if($data->province_id==$province->id)
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
                                                @if($data->district_id==$district->id)
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
                                                @if($data->municipality_id==$municipality->id)
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
                                <label for="ward">Ward No</label>
                                <input type="text" class="form-control" value="{{ $data->ward ?? '' }}" name="ward"
                                       aria-describedby="help" placeholder="Enter Ward No"
                                >
                                @if ($errors->has('ward'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                                <label for="tole">Tole</label>
                                <input type="text" class="form-control" value="{{ $data->tole ?? '' }}" name="tole"
                                       aria-describedby="help" placeholder="Enter Tole"
                                >
                                @if ($errors->has('tole'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                <label for="name">Emergency Contact One</label>
                                <input type="text" class="form-control" value="{{ $data->emergency_contact_one ?? '' }}"
                                       name="emergency_contact_one" aria-describedby="help"
                                       placeholder="Enter Emergency Contact One"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                <label for="name">Emergency Contact Two</label>
                                <input type="text" class="form-control" value="{{ $data->emergency_contact_two ?? '' }}"
                                       name="emergency_contact_two" aria-describedby="help"
                                       placeholder="Enter Emergency Contact Two">
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Currently symptomatic</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="symptoms_recent" class="symptoms_recent"
                                               {{ $data->symptoms_recent == "0" ? 'checked' : '' }} value="0"
                                               data-rel="earning" checked onclick="toggleDateOnset(true)">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ $data->symptoms_recent == "1" ? 'checked' : '' }} name="symptoms_recent" class="symptoms_recent"
                                               value="1" data-rel="earning" onclick="toggleDateOnset(false)">Yes
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Have any symptoms of Covid-19 seen anytime during the past 2 weeks?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="symptoms_within_four_week"
                                               {{ $data->symptoms_two_weeks == "0" ? 'checked' : '' }} value="0"
                                               data-rel="earning" checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ $data->symptoms_two_weeks == "1" ? 'checked' : '' }} name="symptoms_within_four_week"
                                               value="1" data-rel="earning">Yes
                                    </label>
                                </div>
                            </div>
                            <div class="is-symptomatic" style="display: none;">
                                <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom') ? 'has-error' : '' }}">
                                    <label for="date_of_onset_of_first_symptom">Date of onset of first symptom:</label>
                                    <input type="text" class="form-control" value="{{ $data->date_of_onset_of_first_symptom }}"
                                        name="date_of_onset_of_first_symptom" aria-describedby="help">
                                    @if ($errors->has('date_of_onset_of_first_symptom'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom') }}</small>
                                    @endif
                                </div>
                                <?php $symptoms = $data->symptoms ? json_decode($data->symptoms) : []; ?>
                                <div class="form-group" id="symptomatic-patient">
                                    <label class="control-label" for="symptoms">Symptomatic patient with:</label><br>
                                    <input type="checkbox" name="symptoms[]" value="1" @if(in_array(1, $symptoms)) checked @endif>Pneumonia<br>
                                    <input type="checkbox" name="symptoms[]" value="2" @if(in_array(2, $symptoms)) checked @endif>ARDS<br>
                                    <input type="checkbox" name="symptoms[]" value="3" @if(in_array(3, $symptoms)) checked @endif>Influenza-like illness<br>
                                    <input type="checkbox" name="symptoms[]" value="4" @if(in_array(4, $symptoms)) checked @endif>History of fever/chills<br>
                                    <input type="checkbox" name="symptoms[]" value="5" @if(in_array(4, $symptoms)) checked @endif>General weakness<br>
                                    <input type="checkbox" name="symptoms[]" value="6" @if(in_array(5, $symptoms)) checked @endif>Cough<br>
                                    <input type="checkbox" name="symptoms[]" value="7" @if(in_array(6, $symptoms)) checked @endif>Sore Throat<br>
                                    <input type="checkbox" name="symptoms[]" value="8" @if(in_array(7, $symptoms)) checked @endif>Running nose<br>
                                    <input type="checkbox" name="symptoms[]" value="9" @if(in_array(8, $symptoms)) checked @endif>Shortness of breath<br>
                                    <input type="checkbox" name="symptoms[]" value="10" @if(in_array(10, $symptoms)) checked @endif>Irritability/Confusion<br>
                                    <input type="checkbox" name="symptoms[]" value="11" @if(in_array(11, $symptoms)) checked @endif>Loss of taste<br>
                                    <input type="checkbox" name="symptoms[]" value="12" @if(in_array(12, $symptoms)) checked @endif>Loss of smell<br>
                                    <div style="padding: 10px;">
                                        <label>Pain</label><br>
                                        <input type="checkbox" name="symptoms[]" value="13" @if(in_array(13, $symptoms)) checked @endif>Muscular<br>
                                        <input type="checkbox" name="symptoms[]" value="14" @if(in_array(14, $symptoms)) checked @endif>Chest<br>
                                        <input type="checkbox" name="symptoms[]" value="15" @if(in_array(15, $symptoms)) checked @endif>Abdominal<br>
                                        <input type="checkbox" name="symptoms[]" value="16" @if(in_array(16, $symptoms)) checked @endif>Joint<br>
                                    </div>
                                    <input type="checkbox" name="symptoms[]" value="17" @if(in_array(17, $symptoms)) checked @endif>Diarrhea<br>
                                    <input type="checkbox" name="symptoms[]" value="18" @if(in_array(18, $symptoms)) checked @endif>Nausea/vomiting<br>
                                    <input type="checkbox" name="symptoms[]" value="19" @if(in_array(19, $symptoms)) checked @endif>Headache<br>
                                    <input type="checkbox" name="symptoms[]" value="20" @if(in_array(20, $symptoms)) checked @endif>Pharyngeal exudate<br>
                                    <input type="checkbox" name="symptoms[]" value="21" @if(in_array(21, $symptoms)) checked @endif>Conjunctival injection(eye)<br>
                                    <input type="checkbox" name="symptoms[]" value="22" @if(in_array(22, $symptoms)) checked @endif>Seizure<br>
                                    <input type="checkbox" name="symptoms[]" value="23" @if(in_array(23, $symptoms)) checked @endif>Coma<br>
                                    <input type="checkbox" name="symptoms[]" value="24" @if(in_array(24, $symptoms)) checked @endif>Dyspnea/tachynea(DB/Fast breathing)<br>
                                    <input type="checkbox" name="symptoms[]" value="25" @if(in_array(25, $symptoms)) checked @endif>Abnormal lung auscultation<br>
                                    <input type="checkbox" name="symptoms[]" value="26" @if(in_array(26, $symptoms)) checked @endif>Abnormal lung x-ray/CT scan findings<br>
                                    @if ($errors->has('symptoms'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('symptoms') }}</small>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('symptoms_specific') ? 'has-error' : '' }}">
                                    <label for="symptoms_specific">If other specify</label>
                                    <input type="text" class="form-control" value="{{ $data->symptoms_specific }}" name="symptoms_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_specific') }}</small>
                                    @endif
                                </div>

                                <?php $symptoms_comorbidity = $data->symptoms_comorbidity ? json_decode($data->symptoms_comorbidity) : []; ?>
                                <div class="form-group" id="symptoms_comorbidity">
                                    <label class="control-label" for="symptoms_comorbidity">Symptomatic patient with comorbidity</label><br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="1" @if(in_array(1, $symptoms_comorbidity)) selected @endif>Diabetes<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="2" @if(in_array(2, $symptoms_comorbidity)) selected @endif>HTN<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="3" @if(in_array(3, $symptoms_comorbidity)) selected @endif>Hermodialysis<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="4" @if(in_array(4, $symptoms_comorbidity)) selected @endif>Immunocompromised<br>
                                    <div style="padding: 10px;">
                                        <label>Pregnancy(Trimester)</label><br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="5" @if(in_array(5, $symptoms_comorbidity)) selected @endif>First<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="16" @if(in_array(16, $symptoms_comorbidity)) selected @endif>Second<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="17" @if(in_array(17, $symptoms_comorbidity)) selected @endif>Third<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="">No<br>
                                    </div>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="6" @if(in_array(6, $symptoms_comorbidity)) selected @endif>Maternity<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="7" @if(in_array(7, $symptoms_comorbidity)) selected @endif>Heart disease, including hypertension<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="8" @if(in_array(8, $symptoms_comorbidity)) selected @endif>Liver disease<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="9" @if(in_array(9, $symptoms_comorbidity)) selected @endif>Nerve related diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="10" @if(in_array(10, $symptoms_comorbidity)) selected @endif>Kidney diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="11" @if(in_array(11, $symptoms_comorbidity)) selected @endif>Malnutrition<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="12" @if(in_array(12, $symptoms_comorbidity)) selected @endif>Autoimmune diseases<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="13" @if(in_array(13, $symptoms_comorbidity)) selected @endif>Immunodeficiency, including HIV<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="14" @if(in_array(14, $symptoms_comorbidity)) selected @endif>Malignancy<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="15" @if(in_array(15, $symptoms_comorbidity)) selected @endif>Chric lung disesase/asthma/artery<br>
                                    @if ($errors->has('symptoms_comorbidity'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('symptoms_comorbidity') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('symptoms_comorbidity_specific') ? 'has-error' : '' }}">
                                    <label for="symptoms_comorbidity_specific">If other specify</label>
                                    <input type="text" class="form-control" value="{{ $data->symptoms_comorbidity_specific }}" name="symptoms_comorbidity_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_comorbidity_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="caste">Occupation</label>
                                <select name="occupation" class="form-control">
                                    <option {{ $data->occupation == '' ? "selected" : "" }} value="">Select Occupation</option>
                                    <option {{ $data->occupation == '1' ? "selected" : "" }} value="1">Front Line Health Worker</option>
                                    <option {{ $data->occupation == '2' ? "selected" : "" }} value="2">Doctor</option>
                                    <option {{ $data->occupation == '3' ? "selected" : "" }} value="3">Nurse</option>
                                    <option {{ $data->occupation == '4' ? "selected" : "" }} value="4">Police/Army</option>
                                    <option {{ $data->occupation == '5' ? "selected" : "" }} value="5">Business/Industry</option>
                                    <option {{ $data->occupation == '6' ? "selected" : "" }} value="6">Teacher/Student/Education</option>
                                    <option {{ $data->occupation == '7' ? "selected" : "" }} value="7">Civil Servant</option>
                                    <option {{ $data->occupation == '8' ? "selected" : "" }} value="8">Journalist</option>
                                    <option {{ $data->occupation == '9' ? "selected" : "" }} value="9">Agriculture</option>
                                    <option {{ $data->occupation == '10' ? "selected" : "" }} value="10">Transport/Delivery</option>
                                    <option {{ $data->occupation == '12' ? "selected" : "" }} value="12">Tourist</option>
                                    <option {{ $data->occupation == '13' ? "selected" : "" }} value="13">Migrant Worker</option>
                                    <option {{ $data->occupation == '11' ? "selected" : "" }} value="11">Other</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Is Detected From Contract Tracing ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="is_detected" value="no">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" checked name="is_detected"
                                               value="yes">Yes
                                    </label>
                                    <div class="form-group">
                                        <label class="form-label">Parent Case ID</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="{{ $data->parent_case_id }}"
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
                                               {{ $data->travelled_14_days == "0" ? 'checked' : '' }} name="travelled" value="0"
                                               checked>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ $data->travelled_14_days == "1" ? 'checked' : '' }} name="travelled"
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


                            {{-- <div class="form-group">
                                <label class="control-label">Have you ever received Covid-19 vaccine?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_status') == "0" ? 'checked' : '' }} name="vaccine_status"
                                               value="0" class="vaccine_status" required>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_status') == "1" ? 'checked' : '' }} name="vaccine_status"
                                               value="1" class="vaccine_status">Yes
                                    </label>

                                </div>
                            </div>

                            <div class="form-group vaccine-status">
                                <label class="control-label">Do you have a vaccination card?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_card') == "0" ? 'checked' : '' }} name="vaccination_card"
                                               value="0" class="vaccination_card">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_card') == "1" ? 'checked' : '' }} name="vaccination_card"
                                               value="1" class="vaccination_card">Yes
                                    </label>

                                </div>
                            </div>

                            <div class="form-group vaccine-status">
                                <label class="control-label">Vaccination doses complete?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_dosage_complete') == "0" ? 'checked' : '' }} name="vaccination_dosage_complete"
                                               value="0" class="vaccination_dosage_complete">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_dosage_complete') == "1" ? 'checked' : '' }} name="vaccination_dosage_complete"
                                               value="1" class="vaccination_dosage_complete">Yes
                                    </label>

                                </div>
                            </div>

                            <div class="form-group vaccine-status">
                                <label class="control-label">How many dosages of vaccine you have received?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_dosage_count') == "1" ? 'checked' : '' }} name="vaccine_dosage_count"
                                               value="1" class="vaccine_dosage_count">1st Dose
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_dosage_count') == "2" ? 'checked' : '' }} name="vaccine_dosage_count"
                                               value="2" class="vaccine_dosage_count">2nd (Final) Dose
                                    </label>

                                </div>
                            </div>

                            <div class="form-group vaccine-status {{ $errors->has('vaccine_dosage') ? 'has-error' : '' }}">
                                <label for="vaccine_name">Name of Vaccine</label>
                                <select name="vaccine_dosage" class="form-control">
                                    <option {{ old('vaccine_dosage') == '' ? "selected" : "" }} value="">Select Name of Vaccine</option>
                                    <option {{ old('vaccine_dosage') == '1' ? "selected" : "" }} value="1">Verocell (Sinopharm)</option>
                                    <option {{ old('vaccine_dosage') == '2' ? "selected" : "" }} value="2">Covishield (The Serum Institute of India)</option>
                                    <option {{ old('vaccine_dosage') == '3' ? "selected" : "" }} value="3">Pfizer</option>
                                    <option {{ old('vaccine_dosage') == '4' ? "selected" : "" }} value="4">Moderna</option>
                                    <option {{ old('vaccine_dosage') == '5' ? "selected" : "" }} value="5">AstraZeneca</option>
                                    <option {{ old('vaccine_dosage') == '10' ? "selected" : "" }} value="10">Other</option>
                                </select>
                            </div> --}}
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
                                            Nasopharyngeal
                                            <input type="checkbox" name="sample_type[]" value="1" class="sample_type" style="float: left">
                                        </div>
                                        <div class="control-group">
                                            Oropharyngeal
                                            <input type="checkbox" name="sample_type[]" value="2" class="sample_type" style="float: left">
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
                                            <h5>Symptomatic</h5>
                                            <input type="radio" name="infection_type" value="1" id="infection_type" style="bottom: 12px;">
                                        </label>
                                        <label class="radio-inline">
                                            <h5>Asymptomatic</h5>
                                            <input type="radio" name="infection_type" value="2" style="bottom: 12px;">
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
                                            <h5>Paid Service</h5>
                                            <input type="radio" name="service_type" value="1" id="service_type" style="bottom: 12px;">
                                        </label>
                                        <label class="radio-inline">
                                            <h5>Free of cost service</h5>
                                            <input type="radio" name="service_type" value="2" class="service_type" style="bottom: 12px;">
                                        </label>
                                    </div>
                                    @if ($errors->has('service_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('service_type') }}</small>
                                    @endif
                                </div>

                                <div class="panel panel-danger pcr-part">
                                    <div class="panel-heading"><strong>Auto Generated Sample ID is :</strong></div>
                                    <?php
                                        $id = App\Models\OrganizationMember::where('token', auth()->user()->token)->first()->id;
                                        $time = explode(':', Carbon\Carbon::now()->format('H:i:s'));
                                        $converted_time = ($time[0] * 3600) + ($time[1] * 60) + $time[2];
                                        $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon\Carbon::now()->format('ymd') . '-' . $converted_time;
                                        $swab_id = generate_unique_sid($swab_id);
                                    ?>
                                    <div class="panel-body text-center"><h3>{{ $swab_id }}</h3></div>
                                </div>

                                <div class="form-group antigen-part">
                                    <label class="control-label">Enter Registered Lab Id (Unique): </label>
                                    <div class="anti-lab-id" style="display: flex;">
                                        <span style="margin-top: 6px;">{{ Carbon\Carbon::now()->format('ymd') }}-</span>
                                        <input class="form-control" type="text" name="lab_token" id="lab_token"/> 
                                    </div>
                                </div>
                                <input type="text" name="token" value="{{$swab_id}}" hidden>
                                <input type="text" name="case_id" value="{{ $data->case_id }}" hidden>
                                <input type="text" name="case_token" value="{{ $data->token }}" hidden>
                            </div>
                            <button type="submit" id="submit-form" class="btn btn-primary btn-sm btn-block ">SAVE</button>

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

        if($('.symptoms_recent:checked').val() == '1'){
            $('.is-symptomatic').show();
        }else {
            $('.is-symptomatic').hide();
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
            swabFormShow();
        });
        function swabFormShow() {
            if($('.swab_collection_conformation:checked').val() == '1'){
                $('.swab-data').show();
                $("#service_type").prop('required',true);
                $("#infection_type").prop('required',true);
                if($('.service_for:checked').val() == '1') {
                    $(".sample_type").prop('required',true);
                } else {
                    $(".sample_type").prop('required',true);
                }
            }
            else {
                $('.swab-data').hide();
                $("input").prop('required',false);
                $("#infection_type").prop('required',false);
                $(".sample_type").prop('required',false);
            }
        }

        pcrOrAntigenCheck();
        $('.service_for').on('change', function() {
            pcrOrAntigenCheck();
        });
        function pcrOrAntigenCheck(){
            if($('.service_for:checked').val() == '2'){
                $('.antigen-part').show();
            }
            else {
                $('.antigen-part').hide();
            }
        }

        vaccineStatusCheck();
        $('.vaccine_status').on('change', function() {
            vaccineStatusCheck();
        });
        function vaccineStatusCheck() {
            if($('.vaccine_status:checked').val() == '1'){
                $('.vaccine-status').show();
            }
            else {
                $('.vaccine-status').hide();
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
                        digits: true
                    },
                    tole: {
                        required: true,
                    },
                    emergency_contact_one: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        phoneCustom: true
                    },
                    emergency_contact_two: {
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        phoneCustom: true
                    },
                    occupation: {
                        required: true,
                    },
                    lab_token: {
                        required:  function () {
                            return $(".service_for:checked").val() == "2";
                        },
                        maxlength: 8
                    }
                },
                // Specify validation error messages
                messages: {
                    name: "Please provide a valid name.",
                    age: "Please provide a valid age.",

                },
                submitHandler: function (form) {
                    form.submit();
                },
                errorPlacement: function(error, element) {
                  enableSubmit();
                }
            });
        });

        function disableSubmit() {
          $("#submit-form").prop('disabled', true);
          $("#submit-form").html("SAVING...");
          return false;
        }

        function enableSubmit() {
          $("#submit-form").prop('disabled', false);
          $("#submit-form").html("SAVE");
          return false;
        }

    </script>
@endsection