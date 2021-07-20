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
                    <div class="panel-heading text-center">
                        <strong>Contact Interview Form (1 of 2)</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('b-one-form.part-two.update'), ['name' => 'createCase']) !!}
                        <div class="panel-body">

                            <div class="part-one">
                                <h4>Contacts Clinical Informations</h4><br>

                                <div class="form-group">
                                    <label class="control-label">Currently symptomatic</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_recent" class="symptoms_recent"
                                            {{ isset($data) && $data->symptoms_recent == '1' ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_recent" class="symptoms_recent"
                                                {{ isset($data) && $data->symptoms_recent == '0' ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group symptoms_two_weeks_class">
                                    <label class="control-label">Has the contact had any symptoms related to COVID-19 any time after exposure with the case?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_two_weeks" class="symptoms_two_weeks"
                                                {{ isset($data) && $data->symptoms_two_weeks == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_two_weeks" class="symptoms_two_weeks"
                                                {{ isset($data) && $data->symptoms_two_weeks == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="is-symptomatic">
                                    <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom_np') ? 'has-error' : '' }}">
                                        <label for="date_of_onset_of_first_symptom_np">Date of onset of first symptom:</label>
                                        <input type="text" class="form-control" id="date_of_onset_of_first_symptom_np"
                                            name="date_of_onset_of_first_symptom_np" value="{{ isset($data) ? $data->date_of_onset_of_first_symptom : '' }}" aria-describedby="help">
                                        @if ($errors->has('date_of_onset_of_first_symptom_np'))
                                            <small id="help"
                                                class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom_np') }}</small>
                                        @endif
                                    </div>

                                    <?php
                                        if(isset($data)){
                                            $symptoms = $data->symptoms ? json_decode($data->symptoms) : [];    
                                        }else {
                                            $symptoms = [];
                                        }
                                    ?>
                                    
                                    <div class="form-group" id="symptomatic-data">
                                        <label class="control-label" for="symptoms">(Check any that apply):</label><br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="4" @if(in_array(4, $symptoms)) checked @endif> Fever<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="6" @if(in_array(6, $symptoms)) checked @endif> Cough<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="5" @if(in_array(5, $symptoms)) checked @endif> General weakness / Tiredess<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="19" @if(in_array(19, $symptoms)) checked @endif> Headache<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="13" @if(in_array(13, $symptoms)) checked @endif> Pain in the muscles<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="7" @if(in_array(7, $symptoms)) checked @endif> Sore Throat<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="8" @if(in_array(8, $symptoms)) checked @endif> Runny nose<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="9" @if(in_array(9, $symptoms)) checked @endif> Shortness of breath<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="18" @if(in_array(18, $symptoms)) checked @endif> Nausea / Vomiting / Loss of appetite<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="17" @if(in_array(17, $symptoms)) checked @endif> Diarrhea<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="10" @if(in_array(10, $symptoms)) checked @endif> Irritability / Confusion<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="12" @if(in_array(12, $symptoms)) checked @endif> Recent loss of smell<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="11" @if(in_array(11, $symptoms)) checked @endif> Recent loss of taste<br>
                                        {{-- <input type="checkbox" class="symptoms" name="symptoms[]" value="0" @if(in_array(0, $symptoms)) checked @endif> Others<br> --}}
                                        @if ($errors->has('symptoms'))
                                            <small id="help"
                                                class="form-text text-danger">{{ $errors->first('symptoms') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group symptoms_other_class {{ $errors->has('symptoms_specific') ? 'has-error' : '' }}">
                                        <label for="symptoms_specific">If other, specify</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->symptoms_specific : '' }}" name="symptoms_specific"
                                            aria-describedby="help" placeholder="Enter other symptoms"
                                        >
                                        @if ($errors->has('symptoms_specific'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_specific') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="part-two">
                                <h4>Contact pre-existing condition(s)</h4><br>

                                <?php
                                    if(isset($data)){
                                        $symptoms_comorbidity = $data->symptoms_comorbidity ? json_decode($data->symptoms_comorbidity) : [];    
                                    }else {
                                        $symptoms_comorbidity = [];
                                    }
                                ?>
                                <div class="form-group" id="symptoms_comorbidity">
                                    <label class="control-label" for="symptoms_comorbidity">(Check any that apply)</label><br>
                                    <div style="padding: 10px;">
                                        Pregnancy (Trimester)<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="5" @if(in_array(5, $symptoms_comorbidity)) checked @endif> First<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="16" @if(in_array(16, $symptoms_comorbidity)) checked @endif> Second<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value="17" @if(in_array(17, $symptoms_comorbidity)) checked @endif> Third<br>
                                        <input type="radio" name="symptoms_comorbidity_trimester" value=""> No<br>
                                    </div>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="18" @if(in_array(18, $symptoms_comorbidity)) checked @endif> Post delivery (< 6 weeks)<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="7" @if(in_array(7, $symptoms_comorbidity)) checked @endif> Cardiovascular disease, including hypertension<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="1" @if(in_array(1, $symptoms_comorbidity)) checked @endif> Diabetes<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="14" @if(in_array(14, $symptoms_comorbidity)) checked @endif> Malignancy<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="19" @if(in_array(19, $symptoms_comorbidity)) checked @endif> COPD<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="10" @if(in_array(10, $symptoms_comorbidity)) checked @endif> Chronic kidney diseases<br>
                                    {{-- <input type="checkbox" name="symptoms_comorbidity[]" value="0" @if(in_array(10, $symptoms_comorbidity)) checked @endif>Others<br> --}}
                                    @if ($errors->has('symptoms_comorbidity'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('symptoms_comorbidity') }}</small>
                                    @endif
                                </div>
    
                                <div class="form-group {{ $errors->has('symptoms_comorbidity_specific') ? 'has-error' : '' }}">
                                    <label for="symptoms_comorbidity_specific">If other, specify</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->symptoms_comorbidity_specific : '' }}" name="symptoms_comorbidity_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_comorbidity_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <h4>Occupation</h4><br>
                                <select name="occupation" class="form-control occupation">
                                    <option {{ isset($data) && $data->occupation == '' ? "selected" : "" }} value="">Select Occupation
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '1' ? "selected" : "" }} value="1">Health care worker
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '14' ? "selected" : "" }} value="14">Working with animals</option>
                                    <option {{ isset($data) && $data->occupation == '6' ? "selected" : "" }} value="6">Student/Teacher</option>
                                    <option {{ isset($data) && $data->occupation == '16' ? "selected" : "" }} value="16">Security Personnel
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '17' ? "selected" : "" }} value="17">Waste Management Worker
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '18' ? "selected" : "" }} value="18">Hotel/Restaurants/Bars
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '19' ? "selected" : "" }} value="19">Bank and Finance worker
                                    </option>
                                    <option {{ isset($data) && $data->occupation == '0' ? "selected" : "" }} value="0">Others</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
    
                            <div class="form-group occupation_other_class {{ $errors->has('occupaton_other') ? 'has-error' : '' }}">
                                <label for="occupaton_other">If other, specify</label>
                                <input type="text" class="form-control" value="{{ isset($data) ? $data->occupaton_other : '' }}" name="occupaton_other"
                                       aria-describedby="help" placeholder="Enter other occupation"
                                >
                                @if ($errors->has('occupaton_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('occupaton_other') }}</small>
                                @endif
                            </div>

                            <div class="occupation_health_worker_class">
                                <div class="form-group {{ $errors->has('healthworker_title') ? 'has-error' : '' }}">
                                    <label for="healthworker_title">Job Title</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_title : '' }}" name="healthworker_title"
                                        aria-describedby="help" placeholder="Enter Job Title"
                                    >
                                    @if ($errors->has('healthworker_title'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_title') }}</small>
                                    @endif

                                    <label for="healthworker_workplace">Name of the work place</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_workplace : '' }}" name="healthworker_workplace"
                                        aria-describedby="help" placeholder="Name of the work place"
                                    >
                                    @if ($errors->has('healthworker_workplace'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_workplace') }}</small>
                                    @endif

                                    <label>Station</label><br>

                                    <select name="healthworker_station" class="form-control">
                                        <option {{ isset($data) && $data->healthworker_station == '' ? "selected" : "" }} value="">Select Station
                                        </option>
                                        <option {{ isset($data) && $data->healthworker_station == '1' ? "selected" : "" }} value="1">Fever Clinic
                                        </option>
                                        <option {{ isset($data) && $data->healthworker_station == '2' ? "selected" : "" }} value="2">Isolation Ward</option>
                                        <option {{ isset($data) && $data->healthworker_station == '3' ? "selected" : "" }} value="3">ICU/Ventilator</option>
                                        <option {{ isset($data) && $data->healthworker_station == '4' ? "selected" : "" }} value="4">Lab
                                        <option {{ isset($data) && $data->healthworker_station == '0' ? "selected" : "" }} value="0">Others</option>
                                    </select>
                                    @if ($errors->has('healthworker_station'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('healthworker_station') }}</small>
                                    @endif

                                    <label for="healthworker_station_other">If others, specfiy</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_station_other : '' }}" name="healthworker_station_other"
                                        aria-describedby="help" placeholder="Enter other station"
                                    >
                                    @if ($errors->has('healthworker_station_other'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_station_other') }}</small>
                                    @endif


                                    <div class="form-group">
                                        <label class="control-label">Was appropriate PPE used?</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_ppe" class="healthworker_ppe"
                                                    {{ isset($data) && $data->healthworker_ppe == "1" ? 'checked' : '' }} value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_ppe" class="healthworker_ppe"
                                                    {{ isset($data) && $data->healthworker_ppe == "0" ? 'checked' : '' }} value="0">No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="healthworker_first_date">Date of first contact</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_first_date : '' }}" name="healthworker_first_date"
                                            aria-describedby="help" placeholder="Enter Date of first contact"
                                        >
                                        @if ($errors->has('healthworker_first_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_first_date') }}</small>
                                        @endif

                                        <label for="healthworker_last_date">Date of last contact</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_last_date : '' }}" name="healthworker_last_date"
                                            aria-describedby="help" placeholder="Enter Date of last contact"
                                        >
                                        @if ($errors->has('healthworker_last_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_last_date') }}</small>
                                        @endif

                                        <label for="healthworker_narrative">Any relevant narrative</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->healthworker_narrative : '' }}" name="healthworker_narrative"
                                            aria-describedby="help" placeholder="Enter relevant narrative"
                                        >
                                        @if ($errors->has('healthworker_narrative'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_narrative') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Based on the exposure history, classification of the contact</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_classify" class="healthworker_classify"
                                                    {{ isset($data) && $data->healthworker_classify == "1" ? 'checked' : '' }} value="1">Close
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_classify" class="healthworker_classify"
                                                    {{ isset($data) && $data->healthworker_classify == "2" ? 'checked' : '' }} value="2">Casual
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="part-four">
                                <h4>General Exposure Information</h4><br>

                                <div class="form-group">
                                    <label>Has the contact travelled in last 14 days?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="travelled_14_days" class="travelled_14_days"
                                            {{ $data && $data->travelled_14_days == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="travelled_14_days" class="travelled_14_days"
                                            {{ $data && $data->travelled_14_days == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>

                                <div class="travelled_14_days_yes_class">
                                    <div class="form-group">
                                        <label for="date_14_days">Date of travel</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->date_14_days : '' }}" name="date_14_days"
                                            aria-describedby="help" placeholder="Enter Date of travel"
                                        >
                                        @if ($errors->has('date_14_days'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('date_14_days') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>Travel Detail</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="travel_type" class="travel_type"
                                                {{ $data && $data->travel_type == "1" ? 'checked' : '' }} value="1">Domestic Travel
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="travel_type" class="travel_type"
                                                {{ $data && $data->travel_type == "2" ? 'checked' : '' }} value="2">International
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="modes_of_travel">Modes of travel</label>
                                        <select name="modes_of_travel" class="form-control modes_of_travel">
                                            <option value=""  {{ isset($data) && $data->modes_of_travel == "" ? 'selected' : "" }}>Select Measures Taken</option>
                                            <option value="1" {{ isset($data) && $data->modes_of_travel == "1" ? 'selected' : "" }}>Air</option>
                                            <option value="2" {{ isset($data) && $data->modes_of_travel == "2" ? 'selected' : "" }}>Public transport (Bus, Micro, Truck, Taxi,etc)</option>
                                            <option value="3" {{ isset($data) && $data->modes_of_travel == "3" ? 'selected' : "" }}>Private vehicle</option>
                                            <option value="0" {{ isset($data) && $data->modes_of_travel == "0" ? 'selected' : "" }}>Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group modes_of_travel_other_class">
                                        <label for="modes_of_travel_other"></label>If other, specify</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->modes_of_travel_other : '' }}" name="modes_of_travel_other"
                                            aria-describedby="help" placeholder="Enter other modes of travel">
                                        @if ($errors->has('modes_of_travel_other'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('modes_of_travel_other') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="travel_place"></label>Place Visited</label>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->travel_place : '' }}" name="travel_place"
                                            aria-describedby="help" placeholder="Enter Place Visited">
                                        @if ($errors->has('travel_place'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('travel_place') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>In the past 14 days, has the contact had contact with anyone with suspected on confirmed COVID 19 infection?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="contact_status" class="contact_status"
                                            {{ $data && $data->contact_status == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="contact_status" class="contact_status"
                                            {{ $data && $data->contact_status == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group contact_status_yes_class">
                                    <label for="contact_last_date"></label>Date of last contact</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->contact_last_date : '' }}" name="contact_last_date"
                                           aria-describedby="help" placeholder="Enter Date of last contact">
                                    @if ($errors->has('contact_last_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('contact_last_date') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>has the contact visited social gathering/meetings/events/temples/markets/halls etc.</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="contact_social_status" class="contact_social_status"
                                            {{ $data && $data->contact_social_status == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="contact_social_status" class="contact_social_status"
                                            {{ $data && $data->contact_social_status == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group contact_social_status_yes_class">
                                    <label for="contact_social_last_date"></label>Date of last visit</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->contact_social_last_date : '' }}" name="contact_social_last_date"
                                           aria-describedby="help" placeholder="Enter Date of last visit">
                                    @if ($errors->has('contact_social_last_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('contact_social_last_date') }}</small>
                                    @endif
                                </div>
                            </div>


                            <div class="part-four">
                                <h4>Vaccination Status</h4><br>

                                <div class="form-group">
                                    <label class="control-label">Has the Case under Investigation received SARS-CoV-2 vaccine (COVID-19 vaccine)?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ isset($data) && $data->sars_cov2_vaccinated == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ isset($data) && $data->sars_cov2_vaccinated == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ isset($data) && $data->sars_cov2_vaccinated == "2" ? 'checked' : '' }} value="2">Unknown
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group sars_cov2_vaccinated_yes_class">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th colspan="2">Name of the vaccine (Product/Brand Name)</th>
                                                    <th>Date of Vaccination</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-sars-cov-tbody text-center">
                                                <tr class="table-sars-cov-tr">
                                                    <td>
                                                        Dose 1
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_one_name" value="{{ isset($data) ? $data->dose_one_name : '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_one_date" id="dose_one_date" value="{{ isset($data) ? $data->dose_one_date : '' }}">
                                                    </td>
                                                </tr>
                                                <tr class="table-sars-cov-tr">
                                                    <td>
                                                        Dose 2
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_two_name" value="{{ isset($data) ? $data->dose_two_name : '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_two_date" id="dose_two_date" value="{{ isset($data) ? $data->dose_two_date : '' }}">
                                                    </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="part-four">
                                <h4>Contact Management</h4><br>

                                <label> (Measures taken:)</label><br>
                                <div class="form-group">
                                    <select name="measures_taken" class="form-control measures_taken">
                                        <option value="" disabled selected>Select Measures Taken</option>
                                        <option value="1" {{ isset($data) && $data->measures_taken == "1" ? 'selected' : "" }}>Home quarantine</option>
                                        <option value="2" {{ isset($data) && $data->measures_taken == "2" ? 'selected' : "" }}>Referred to Quarantine center</option>
                                        <option value="3" {{ isset($data) && $data->measures_taken == "3" ? 'selected' : "" }}>Contact admitted to hospital</option>
                                        <option value="4" {{ isset($data) && $data->measures_taken == "4" ? 'selected' : "" }}>Contact lost</option>
                                        <option value="0" {{ isset($data) && $data->measures_taken == "0" ? 'selected' : "" }}>Others</option>
                                    </select>
                                </div>
                                <div class="form-group measures_taken_other_class">
                                    <label for="measures_taken_other">If others, specfiy</label>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->measures_taken_other : '' }}" name="measures_taken_other"
                                            aria-describedby="help" placeholder="Enter other measures taken"
                                    >
                                    @if ($errors->has('measures_taken_other'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('measures_taken_other') }}</small>
                                    @endif
                                </div>

                                <div class="form-group measures_taken_hospital_class">
                                    <label>If referred to hospital/quarantine facility:</label>
                                    <div class="form-group">
                                        <label>Referral Date</label><br>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->measures_referral_date : '' }}" name="measures_referral_date"
                                                aria-describedby="help" placeholder="Enter Referral Date"
                                        >
                                        @if ($errors->has('measures_referral_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('measures_referral_date') }}</small>
                                        @endif

                                        <label>Name of hospital/quarantine center </label><br>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->measures_hospital_name : '' }}" name="measures_hospital_name"
                                                aria-describedby="help" placeholder="Enter Name of hospital/quarantine center"
                                        >
                                        @if ($errors->has('measures_hospital_name'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('measures_hospital_name') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="part-four">
                                <h4>Test status of the contact</h4><br>

                                <div class="form-group">
                                    <label class="control-label">Was the contact tested?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="test_status" class="test_status"
                                                {{ isset($data) && $data->test_status == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="test_status" class="test_status"
                                                {{ isset($data) && $data->test_status == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                    </div>
                                </div>

                                <div class="test_status_yes_class">

                                    <div class="form-group">
                                        <label class="control-label">test conducted</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "1" ? 'checked' : '' }} value="1">RT-PCR
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "2" ? 'checked' : '' }} value="2">RDT-Antigen
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "0" ? 'checked' : '' }} value="1">Unknown
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Date of Swab collection </label><br>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->collection_date : '' }}" name="collection_date"
                                                aria-describedby="help" placeholder="Enter Date of Swab collection"
                                        >
                                        @if ($errors->has('collection_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('collection_date') }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">test result</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "1" ? 'checked' : '' }} value="1">Positive
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "2" ? 'checked' : '' }} value="2">Negative
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="test_type" class="test_type"
                                                    {{ isset($data) && $data->test_type == "0" ? 'checked' : '' }} value="1">Unknown
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>test Result Date </label><br>
                                        <input type="text" class="form-control" value="{{ isset($data) ? $data->result_date : '' }}" name="result_date"
                                                aria-describedby="help" placeholder="Enter test Result Date"
                                        >
                                        @if ($errors->has('result_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('result_date') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="part-four">
                                <h4>Follow Status( to be completed at the end of the prescribed follow up period)</h4><br>

                                <select name="followup_status" class="form-control">
                                    <option {{ isset($data) && $data->followup_status == '' ? "selected" : "" }} value="">Select Followup Status
                                    </option>
                                    <option {{ isset($data) && $data->followup_status == '1' ? "selected" : "" }} value="1">contact remain asymptomatic
                                    <option {{ isset($data) && $data->followup_status == '2' ? "selected" : "" }} value="2">Developed symptoms and investigated</option>
                                    <option {{ isset($data) && $data->followup_status == '3' ? "selected" : "" }} value="3">turned into confirmed caser</option>
                                    <option {{ isset($data) && $data->followup_status == '4' ? "selected" : "" }} value="4">death
                                    <option {{ isset($data) && $data->followup_status == '0' ? "selected" : "" }} value="5">Lost/unknown</option>
                                </select>
                                @if ($errors->has('followup_status'))
                                    <small id="help"
                                            class="form-text text-danger">{{ $errors->first('followup_status') }}</small>
                                @endif
                            </div>

                            <div class="part-four">
                                <h4>Data Collector information</h4><br>

                                <div class="form-group">
                                    <label>Form Completion Date </label><br>
                                    <input type="text" class="form-control" value="{{ isset($data) ? $data->completion_date : '' }}" name="completion_date"
                                            aria-describedby="help" placeholder="Enter Form Completion Date"
                                    >
                                    @if ($errors->has('completion_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('completion_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="case_id" value="{{ $data->case_id }}">

                            <button type="submit" class="btn btn-primary btn-sm btn-block ">SAVE</button>
                            
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

    symptomsTwoWeeks();
    $('.symptoms_recent').on('change', function() {
        symptomsTwoWeeks();
    });
    function symptomsTwoWeeks(){
        if($('.symptoms_recent:checked').val() == '0'){
            $('.symptoms_two_weeks_class').show();
            $('.is-symptomatic').hide();
        }
        else {
            $('.symptoms_two_weeks_class').hide();
            $('.is-symptomatic').show();
        }
    }
    $('.symptoms_two_weeks').on('change', function() {
        if($('.symptoms_two_weeks:checked').val() == '1'){
            $('.is-symptomatic').show();
        }else {
            symptomsTwoWeeks();
        }
    });

    occupation();
    $('.occupation').on('change', function() {
        occupation();
    });
    function occupation(){
        if($('.occupation').val() == '0'){
            $('.occupation_other_class').show();
            $('.occupation_health_worker_class').hide();
        }else if($('.occupation').val() == '1'){
            $('.occupation_health_worker_class').show();
            $('.occupation_other_class').hide();
        }else{
            $('.occupation_other_class').hide();
            $('.occupation_health_worker_class').hide();
        }
    }

    travelled_14_days();
    $('.travelled_14_days').on('change', function() {
        travelled_14_days();
    });
    function travelled_14_days(){
        if($('.travelled_14_days:checked').val() == '1'){
            $('.travelled_14_days_yes_class').show();
        }else {
            $('.travelled_14_days_yes_class').hide();
        }
    }

    modes_of_travel();
    $('.modes_of_travel').on('change', function() {
        modes_of_travel();
    });
    function modes_of_travel(){
        if($('.modes_of_travel').val() == '0'){
            $('.modes_of_travel_other_class').show();
        }else {
            $('.modes_of_travel_other_class').hide();
        }
    }

    contact_status();
    $('.contact_status').on('change', function() {
        contact_status();
    });
    function contact_status(){
        if($('.contact_status:checked').val() == '1'){
            $('.contact_status_yes_class').show();
        }else {
            $('.contact_status_yes_class').hide();
        }
    }

    contact_social_status();
    $('.contact_social_status').on('change', function() {
        contact_social_status();
    });
    function contact_social_status(){
        if($('.contact_social_status:checked').val() == '1'){
            $('.contact_social_status_yes_class').show();
        }else {
            $('.contact_social_status_yes_class').hide();
        }
    }

    sars_cov2_vaccinated();
    $('.sars_cov2_vaccinated').on('change', function() {
        sars_cov2_vaccinated();
    });
    function sars_cov2_vaccinated(){
        if($('.sars_cov2_vaccinated:checked').val() == '1'){
            $('.sars_cov2_vaccinated_yes_class').show();
        }else {
            $('.sars_cov2_vaccinated_yes_class').hide();
        }
    }

    measures_taken();
    $('.measures_taken').on('change', function() {
        measures_taken();
    });
    function measures_taken(){
        if($('.measures_taken').val() == '0'){
            $('.measures_taken_other_class').show();
            $('.measures_taken_hospital_class').hide();
        }else if($('.measures_taken').val() == '3'){
            $('.measures_taken_hospital_class').show();
            $('.measures_taken_other_class').hide();
        }else{
            $('.measures_taken_other_class').hide();
            $('.measures_taken_hospital_class').hide();
        }
    }

    test_status();
    $('.test_status').on('change', function() {
        test_status();
    });
    function test_status(){
        if($('.test_status:checked').val() == '1'){
            $('.test_status_yes_class').show();
        }else {
            $('.test_status_yes_class').hide();
        }
    }
    

    
    
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#date_of_onset_of_first_symptom_np').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });

    $('#exposure_ref_period_from_np').nepaliDatePicker({
        language: 'english',
    });

    $('#exposure_ref_period_to_np').nepaliDatePicker({
        language: 'english',
    });

    
    
    var min = 0;
    var max = 1000;

	$('.btn-add-fourteen-days').on('click', function() {
        var random = Math.floor(Math.random() * (max - min + 1)) + min;
		var tr = $(".table-fourteen-days-tr").last();
		var count_row = tr.data("row-id");
		count_row++;

		var new_row = tr.clone()
		.find("input, select").val("").end()
		.show()
		.appendTo(".table-fourteen-days-tbody");

		new_row.attr('data-row-id', count_row);
		new_row.find(".btn-remove-fourteen-days").show();
        new_row.find(".nep-date-departure").attr('id', 'departure_date_' + random).nepaliDatePicker({
			language: 'english',
            disableAfter: currentDate
		});
        new_row.find(".nep-date-arrival").attr('id', 'arrival_date_' + random).nepaliDatePicker({
			language: 'english',
            disableAfter: currentDate
		});
        
		// new_row.find(".nep-date-picker").nepaliDatePicker({
		// 	language: 'english',
        //     disableAfter: currentDate
		// });
	});

    $('body').on('click', '.btn-remove-fourteen-days', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-fourteen-days-tr").remove();
    });

    $('.btn-add-same-household').on('click', function() {
        var tr = $(".table-same-household-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-same-household-tbody");

        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-same-household").show();
        new_row.find(".nep-date-picker").nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
    });

    $('body').on('click', '.btn-remove-same-household', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-same-household-tr").remove();
    });

    $('.btn-add-close-contact').on('click', function() {
        var tr = $(".table-close-contact-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-close-contact-tbody");

        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-close-contact").show();
        new_row.find(".nep-date-picker").nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
    });

    $('body').on('click', '.btn-remove-close-contact', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-close-contact-tr").remove();
    });

    $('.btn-add-direct-care').on('click', function() {
        var tr = $(".table-direct-care-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-direct-care-tbody");

        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-direct-care").show();
        new_row.find(".nep-date-picker").nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
    });

    $('body').on('click', '.btn-remove-direct-care', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-direct-care-tr").remove();
    });

    $('.btn-add-school-attend').on('click', function() {
        var tr = $(".table-school-attend-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-school-attend-tbody");

        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-school-attend").show();
        new_row.find(".nep-date-picker").nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
    });

    $('body').on('click', '.btn-remove-school-attend', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-school-attend-tr").remove();
    });
    </script>
@endsection