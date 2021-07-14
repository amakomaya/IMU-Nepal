@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        #all_form {
            background: #ecf5fc;
            padding: 40px 50px 45px;
        }
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
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
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <form action="" method="GET">
                                <div class="form-group">
                                    <h3>Search case by SID :</h3>
                                    <div class="row">
                                        <div class="container">
                                            <div class="input-group">
                                                <input type="text" name="sid" value="{{ request()->get('sid') }}" class="form-control" placeholder="Enter SID"/>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    @php $initial_token = '' @endphp
                    @if($ancs == [1])
                    @elseif($ancs == null)
                    <div class="panel-body">
                        <h3>No Records Found</h3>
                    </div>
                    @else
                    <div class="panel-body">
                        <form action="{{ route('admin.sid.update') }}" method="POST" id="all_form">
                        @csrf
                        <h2>Patient Data</h2>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" value="{{ $ancs->woman->name }}" name="name"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('name'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                            <label for="age">Age</label>
                            <input type="text" id="age" value="{{ $ancs->woman->age }}" class="form-control col-xs-9"
                                   name="age" placeholder="Enter Age"
                            ><br>
                            <input type="radio" name="age_unit"
                                   {{ $ancs->woman->age_unit == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                   checked>Years
                            <input type="radio" name="age_unit"
                                   {{ $ancs->woman->age_unit == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                            <input type="radio" name="age_unit"
                                   {{ $ancs->woman->age_unit == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                            <br>
                            @if ($errors->has('age'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="caste">Ethnicity</label>
                            <select name="caste" class="form-control">
                                <option {{ $ancs->woman->caste == '6' ? "selected" : "" }} value="6">Don't Know</option>
                                <option {{ $ancs->woman->caste == '0' ? "selected" : "" }} value="0">Dalit</option>
                                <option {{ $ancs->woman->caste == '1' ? "selected" : "" }} value="1">Janajati</option>
                                <option {{ $ancs->woman->caste == '2' ? "selected" : "" }} value="2">Madheshi</option>
                                <option {{ $ancs->woman->caste == '3' ? "selected" : "" }} value="3">Muslim</option>
                                <option {{ $ancs->woman->caste == '4' ? "selected" : "" }} value="4">Brahmin/Chhetri
                                </option>
                                <option {{ $ancs->woman->caste == '5' ? "selected" : "" }} value="5">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="company">Gender</label>
                            <select name="sex" class="form-control">
                                <option value="" disabled selected>Select Gender</option>
                                <option {{ $ancs->woman->sex == '1' ? "selected" : "" }} value="1">Male</option>
                                <option {{ $ancs->woman->sex == '2' ? "selected" : "" }} value="2">Female</option>
                                <option {{ $ancs->woman->sex == '3' ? "selected" : "" }}  value="3">Other</option>
                            </select>
                            @if ($errors->has('sex'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                            <label for="ward">Ward No</label>
                            <input type="text" class="form-control" value="{{ $ancs->woman->ward }}" name="ward"
                                   aria-describedby="help" placeholder="Enter Ward No"
                            >
                            @if ($errors->has('ward'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                            <label for="tole">Tole</label>
                            <input type="text" class="form-control" value="{{ $ancs->woman->tole }}" name="tole"
                                   aria-describedby="help" placeholder="Enter Tole"
                            >
                            @if ($errors->has('tole'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                            <label for="name">Emergency Contact One</label>
                            <input type="text" class="form-control" value="{{ $ancs->woman->emergency_contact_one }}"
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
                            <input type="text" class="form-control" value="{{ $ancs->woman->emergency_contact_two }}"
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
                                    <input type="radio" name="symptoms_recent"
                                           {{ $ancs->woman->symptoms_recent == "0" ? 'checked' : '' }} value="0"
                                           data-rel="earning" class="symptoms_recent">No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="symptoms_recent"
                                           {{ $ancs->woman->symptoms_recent == "1" ? 'checked' : '' }}
                                           value="1" data-rel="earning" class="symptoms_recent">Yes
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Have any symptoms of Covid-19 seen anytime during the past 2 weeks?</label>
                            <div class="control-group">
                                <label class="radio-inline">
                                    <input type="radio" name="symptoms_within_four_week"
                                           {{ $ancs->woman->symptoms_within_four_week == "0" ? 'checked' : '' }} value="0"
                                           data-rel="earning" checked>No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"
                                           {{ $ancs->woman->symptoms_within_four_week == "1" ? 'checked' : '' }} name="symptoms_within_four_week"
                                           value="1" data-rel="earning">Yes
                                </label>
                            </div>
                        </div>
                        <div class="is-symptomatic" style="display: none;">
                            <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom') ? 'has-error' : '' }}">
                                <label for="date_of_onset_of_first_symptom">Date of onset of first symptom:</label>
                                <input type="text" class="form-control" value="{{ $ancs->woman->date_of_onset_of_first_symptom }}"
                                    name="date_of_onset_of_first_symptom" aria-describedby="help">
                                @if ($errors->has('date_of_onset_of_first_symptom'))
                                    <small id="help"
                                        class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom') }}</small>
                                @endif
                            </div>
                            <div class="form-group" id="symptomatic-patient">
                                @php
                                    $symptoms = json_decode($ancs->woman->symptoms ?? '[]');
                                @endphp
                                <label class="control-label" for="symptoms">Symptomatic patient with:</label><br>
                                <input type="checkbox" name="symptoms[]" value="1" @if(in_array(1, $symptoms)) checked @endif>Pneumonia<br>
                                <input type="checkbox" name="symptoms[]" value="2" @if(in_array(2, $symptoms)) checked @endif>ARDS<br>
                                <input type="checkbox" name="symptoms[]" value="3" @if(in_array(3, $symptoms)) checked @endif>Influenza-like illness<br>
                                <input type="checkbox" name="symptoms[]" value="4" @if(in_array(4, $symptoms)) checked @endif>History of fever/chills<br>
                                <input type="checkbox" name="symptoms[]" value="5" @if(in_array(5, $symptoms)) checked @endif>General weakness<br>
                                <input type="checkbox" name="symptoms[]" value="6" @if(in_array(6, $symptoms)) checked @endif>Cough<br>
                                <input type="checkbox" name="symptoms[]" value="7" @if(in_array(7, $symptoms)) checked @endif>Sore Throat<br>
                                <input type="checkbox" name="symptoms[]" value="8" @if(in_array(8, $symptoms)) checked @endif>Running nose<br>
                                <input type="checkbox" name="symptoms[]" value="9" @if(in_array(9, $symptoms)) checked @endif>Shortness of breath<br>
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
                                <input type="text" class="form-control" value="{{ $ancs->woman->symptoms_specific }}" name="symptoms_specific"
                                       aria-describedby="help" placeholder="Enter other symptoms"
                                >
                                @if ($errors->has('symptoms_specific'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_specific') }}</small>
                                @endif
                            </div>
                            <div class="form-group" id="symptoms_comorbidity">
                                @php
                                    $symptoms_comorbidity = json_decode($ancs->woman->symptoms_comorbidity ?? '[]');
                                @endphp
                                <label class="control-label" for="symptoms_comorbidity">Symptomatic patient with comorbidity</label><br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="1" @if(in_array(1, $symptoms_comorbidity)) checked @endif>Diabetes<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="2" @if(in_array(2, $symptoms_comorbidity)) checked @endif>HTN<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="3" @if(in_array(3, $symptoms_comorbidity)) checked @endif>Hermodialysis<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="4" @if(in_array(4, $symptoms_comorbidity)) checked @endif>Immunocompromised<br>
                                <div style="padding: 10px;">
                                    <label>Pregnancy(Trimester)</label><br>
                                    <input type="radio" name="symptoms_comorbidity_trimester" value="5" @if(in_array(5, $symptoms_comorbidity)) checked @endif>First<br>
                                    <input type="radio" name="symptoms_comorbidity_trimester" value="16" @if(in_array(16, $symptoms_comorbidity)) checked @endif>Second<br>
                                    <input type="radio" name="symptoms_comorbidity_trimester" value="17" @if(in_array(17, $symptoms_comorbidity)) checked @endif>Third<br>
                                    <input type="radio" name="symptoms_comorbidity_trimester" value="" @if(!array_intersect([5,16,17], $symptoms_comorbidity)) checked @endif>No<br>
                                </div>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="6" @if(in_array(6, $symptoms_comorbidity)) checked @endif>Maternity<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="7" @if(in_array(7, $symptoms_comorbidity)) checked @endif>Heart disease, including hypertension<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="8" @if(in_array(8, $symptoms_comorbidity)) checked @endif>Liver disease<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="9" @if(in_array(9, $symptoms_comorbidity)) checked @endif>Nerve related diseases<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="10" @if(in_array(10, $symptoms_comorbidity)) checked @endif>Kidney diseases<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="11" @if(in_array(11, $symptoms_comorbidity)) checked @endif>Malnutrition<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="12" @if(in_array(12, $symptoms_comorbidity)) checked @endif>Autoimmune diseases<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="13" @if(in_array(13, $symptoms_comorbidity)) checked @endif>Immunodeficiency, including HIV<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="14" @if(in_array(14, $symptoms_comorbidity)) checked @endif>Malignancy<br>
                                <input type="checkbox" name="symptoms_comorbidity[]" value="15" @if(in_array(15, $symptoms_comorbidity)) checked @endif>Chric lung disesase/asthma/artery<br>
                                @if ($errors->has('symptoms_comorbidity'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('symptoms_comorbidity') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('symptoms_comorbidity_specific') ? 'has-error' : '' }}">
                                <label for="symptoms_comorbidity_specific">If other specify</label>
                                <input type="text" class="form-control" value="{{ $ancs->woman->symptoms_comorbidity_specific }}" name="symptoms_comorbidity_specific"
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
                                <option {{ $ancs->woman->occupation == '' ? "selected" : "" }} value="">Select Occupation
                                </option>
                                <option {{ $ancs->woman->occupation == '1' ? "selected" : "" }} value="1">Front Line Health
                                    Worker
                                </option>
                                <option {{ $ancs->woman->occupation == '2' ? "selected" : "" }} value="2">Doctor</option>
                                <option {{ $ancs->woman->occupation == '3' ? "selected" : "" }} value="3">Nurse</option>
                                <option {{ $ancs->woman->occupation == '4' ? "selected" : "" }} value="4">Police/Army
                                </option>
                                <option {{ $ancs->woman->occupation == '5' ? "selected" : "" }} value="5">
                                    Business/Industry
                                </option>
                                <option {{ $ancs->woman->occupation == '6' ? "selected" : "" }} value="6">
                                    Teacher/Student/Education
                                </option>
                                <option {{ $ancs->woman->occupation == '7' ? "selected" : "" }} value="7">Journalist
                                </option>
                                <option {{ $ancs->woman->occupation == '8' ? "selected" : "" }} value="8">Agriculture
                                </option>
                                <option {{ $ancs->woman->occupation == '9' ? "selected" : "" }} value="9">
                                    Transport/Delivery
                                </option>
                                <option {{ $ancs->woman->occupation == '10' ? "selected" : "" }} value="10">Other</option>
                            </select>
                            @if ($errors->has('occupation'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label">Have you traveled anywhere till 14 days ago?</label>
                            <div class="control-group">
                                <label class="radio-inline">
                                    <input type="radio"
                                           {{ $ancs->woman->travelled == "0" ? 'checked' : '' }} name="travelled" value="0"
                                           checked>No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"
                                           {{ $ancs->woman->travelled == "1" ? 'checked' : '' }} name="travelled"
                                           value="1">Yes
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="case_reason">
                            @php
                                $reasons = json_decode($ancs->woman->reson_for_testing ?? '[]');
                            @endphp
                            <label class="control-label" for="reson_for_testing">Reason for testing:</label><br>
                            <input type="checkbox" name="reson_for_testing[]" value="1" @if(in_array(1, $reasons)) checked @endif>Planned travel<br>
                            <input type="checkbox" name="reson_for_testing[]" value="2" @if(in_array(2, $reasons)) checked @endif>Mandatory requirement<br>
                            <input type="checkbox" name="reson_for_testing[]" value="3" @if(in_array(3, $reasons)) checked @endif>Returnee/Migrant worker<br>
                            <input type="checkbox" name="reson_for_testing[]" value="4" @if(in_array(4, $reasons)) checked @endif>Pre-medical/surgical procedure<br>
                            <input type="checkbox" name="reson_for_testing[]" value="5" @if(in_array(5, $reasons)) checked @endif>Pregnancy complications/Pre-delivery<br>
                            <input type="checkbox" name="reson_for_testing[]" value="6" @if(in_array(6, $reasons)) checked @endif>Testing by Government authority for other purpose<br>
                            <input type="checkbox" name="reson_for_testing[]" value="7" @if(in_array(7, $reasons)) checked @endif>Test on demand by person<br>
                            @if ($errors->has('reson_for_testing'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('reson_for_testing') }}</small>
                            @endif
                        </div>

                        <h2>Lab Data</h2>
                        <div class="form-group">
                            <label for="name">Sample Received Date</label>
                            <input type="text" id="sample_recv_date" class="form-control" value="{{ isset($ancs->labreport) ? $ancs->labreport->sample_recv_date : '' }}" name="sample_recv_date"
                                   aria-describedby="help" placeholder="Enter Sample Received Date">
                            @if ($errors->has('sample_recv_date'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_recv_date') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Date</label>
                            <input type="text" id="sample_test_date" class="form-control" value="{{ isset($ancs->labreport) ? $ancs->labreport->sample_test_date : '' }}" name="sample_test_date"
                                   aria-describedby="help" placeholder="Enter Sample Test Date">
                            @if ($errors->has('sample_test_date'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_date') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Time</label>
                            <input type="text" id="sample_test_time" class="form-control" value="{{ isset($ancs->labreport) ? $ancs->labreport->sample_test_time : '' }}" name="sample_test_time"
                                   aria-describedby="help" placeholder="Enter Sample Test Time">
                            @if ($errors->has('sample_test_time'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_time') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Result</label>
                            <select name="sample_test_result" class="form-control">
                                <option value="" disabled selected>Select Sample Test Result</option>
                                <option {{ isset($ancs->labreport) && $ancs->labreport->sample_test_result == '3' ? "selected" : "" }} value="3">Positive</option>
                                <option {{ isset($ancs->labreport) && $ancs->labreport->sample_test_result == '4' ? "selected" : "" }} value="4">Negative</option>
                                <option {{ isset($ancs->labreport) && $ancs->labreport->sample_test_result == '9' ? "selected" : "" }}  value="9">Received</option>
                                @php if(isset($ancs->labreport) && $ancs->labreport->sample_test_result != 3 && $ancs->labreport->sample_test_result != 4 && $ancs->labreport->sample_test_result != 9) {
                                    $is_select = 'selected';
                                    $is_value = $ancs->labreport->sample_test_result;
                                } else {
                                    $is_select = '';
                                    $is_value = '';
                                }
                                @endphp
                                <option {{ $is_select }} value="{{ $is_value }}">Don't Know</option>
                            </select>
                            @if ($errors->has('sample_test_result'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_result') }}</small>
                            @endif
                        </div>
                        @php
                            if(isset($ancs->labreport)) {
                                $s_token = explode('-', $ancs->labreport->token);
                                $initial_token = $s_token[0];
                                array_shift($s_token);
                                $remaining_token = implode('-', $s_token);
                            } else {
                                $initial_token = '';
                                $remaining_token = '';
                            }
                        @endphp
                        <div class="form-group">
                            <label for="name">Sample Token</label> 
                            <div>
                                {{-- {{ $initial_token }}- --}}
                                <input type="text" id="remaining_token" class="form-control" value="{{ $remaining_token }}" name="remaining_token"
                                       aria-describedby="help" placeholder="Enter Sample Token">
                            </div>
                        </div>

                        <input type="hidden" id="lab_tests_token" name="lab_tests_token" value="{{ isset($ancs->labreport) ? $ancs->labreport->token : '' }}" class="form-control">
                        <input type="hidden" name="woman_token" value="{{ $ancs->woman_token }}" class="form-control">
                        <input type="hidden" name="sid" value="{{ request()->get('sid') }}" class="form-control">

                        {!! rcForm::close('post') !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    var tok = {!! json_encode($initial_token) !!};
    if(tok != ''){
        tok = tok + '-';
    }
    $('#remaining_token').keyup(function() {
        $('#lab_tests_token').val(tok + $('#remaining_token').val());
    });

    symptoms_recent_class_check();
    $('.symptoms_recent').on('change', function() {
        symptoms_recent_class_check();
    });
    function symptoms_recent_class_check() {
    if($('.symptoms_recent:checked').val() == '1')
        $('.is-symptomatic').show();
    else
        $('.is-symptomatic').hide();
    }
</script>
@endsection
