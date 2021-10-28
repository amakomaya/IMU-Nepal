@extends('layouts.backend.app')
@section('style')
    <style>
        .earning {
            display: none;
        }

        .form-cict {
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
                        <strong>Case Investigation (A Form) (2 of 3)</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body form-cict">
                        {!! rcForm::open('POST', route('cict-tracing.section-two.update', $data->case_id), ['name' => 'createCase']) !!}
                        {{ method_field('PUT') }}
                        <div class="panel-body">

                            <div class="part-one">
                                <h4>I. Symptoms</h4><br>

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

                                @if(isset($data->suspectedCase))
                                    @if(isset($data->suspectedCase->latestAnc))
                                        <input type="hidden" id="sample_collection_date" value="{{ $data->suspectedCase->latestAnc->collection_date_en ?? date('Y-m-d') }}">
                                    @else

                                    <input type="hidden" id="sample_collection_date" value="{{ date('Y-m-d') }}">
                                    @endif
                                @else
                                <input type="hidden" id="sample_collection_date" value="{{ date('Y-m-d') }}">
                                @endif

                                <div class="form-group symptoms_two_weeks_class">
                                    <label class="control-label">Whether symptomatic any time during the past two weeks?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_two_weeks" class="symptoms_two_weeks" id="symptoms_two_weeks"
                                                {{ $data && $data->symptoms_two_weeks == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_two_weeks" class="symptoms_two_weeks"
                                                {{ $data && $data->symptoms_two_weeks == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="is-symptomatic">
                                    <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom') ? 'has-error' : '' }}">
                                        <label for="date_of_onset_of_first_symptom">Date of onset of first symptom:</label>
                                        <input type="text" class="form-control" id="date_of_onset_of_first_symptom"
                                            name="date_of_onset_of_first_symptom" value="{{ $data ? $data->date_of_onset_of_first_symptom : '' }}" aria-describedby="help">
                                        @if ($errors->has('date_of_onset_of_first_symptom'))
                                            <small id="help"
                                                class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom') }}</small>
                                        @endif
                                    </div>

                                    <?php
                                        if($data){
                                            $symptoms = $data->symptoms ? json_decode($data->symptoms) : [];    
                                        }else {
                                            $symptoms = [];
                                        }
                                    ?>
                                    
                                    <div class="form-group" id="symptomatic-data">
                                        <label class="control-label" for="symptoms">(Check any that apply):</label><br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="4" @if(in_array(4, $symptoms)) checked @endif> Fever<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="6" @if(in_array(6, $symptoms)) checked @endif> Cough<br>
                                        <input type="checkbox" class="symptoms" name="symptoms[]" value="5" @if(in_array(5, $symptoms)) checked @endif> General weakness / Tiredness<br>
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
                                        <input type="text" class="form-control" value="{{ $data ? $data->symptoms_specific : '' }}" name="symptoms_specific" id="symptoms_specific"
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
                                <h4>II. Underlying medical conditions or disease / comorbidity</h4><br>

                                <?php
                                    if($data){
                                        $symptoms_comorbidity = $data->symptoms_comorbidity ? json_decode($data->symptoms_comorbidity) : [];    
                                    }else {
                                        $symptoms_comorbidity = [];
                                    }
                                ?>
                                <div class="form-group" id="symptoms_comorbidity">
                                    <label class="control-label" for="symptoms_comorbidity">(Check any that apply)</label><br>
                                    <div class="form-group col-md-6">
                                        Pregnancy (Trimester)<br>
                                        <select name="symptoms_comorbidity_trimester" class="form-control">
                                            <option value="">-- Select Option --</option>
                                            <option value="5" @if(in_array(5, $symptoms_comorbidity)) selected @endif>First</option>
                                            <option value="16" @if(in_array(16, $symptoms_comorbidity)) selected @endif>Second</option>
                                            <option value="17" @if(in_array(17, $symptoms_comorbidity)) selected @endif>Third</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="18" @if(in_array(18, $symptoms_comorbidity)) checked @endif> Post delivery (< 6 weeks)<br>
                                    <input type="checkbox" name="symptoms_comorbidity[]" value="7" @if(in_array(7, $symptoms_comorbidity)) checked @endif> Cardiovascular disease or hypertension<br>
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
                                    <input type="text" class="form-control" value="{{ $data ? $data->symptoms_comorbidity_specific : '' }}" name="symptoms_comorbidity_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_comorbidity_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="part-three">
                                <h4>III. High exposure category of Case under Investigation belongs to</h4><br>

                                <div class="form-group">
                                    <select name="high_exposure" class="form-control high_exposure">
                                        <option value="" {{$data && $data->high_exposure == "" ? 'selected' : "" }}>-- Select High Exposure Category --</option>
                                        <option value="1" {{$data && $data->high_exposure == "1" ? 'selected' : "" }}>Health Care Work (any type, level & facility, including cleaning staff</option>
                                        <option value="2" {{$data && $data->high_exposure == "2" ? 'selected' : "" }}>Community Health / Immunization Clinic Volunteer</option>
                                        <option value="3" {{$data && $data->high_exposure == "3" ? 'selected' : "" }}>Sanitary / Waste Collection / Management Worker / Transport Driver / Helper</option>
                                        <option value="4" {{$data && $data->high_exposure == "4" ? 'selected' : "" }}>Patient & Dead Body Transport Driver/ Helper</option>
                                        <option value="5" {{$data && $data->high_exposure == "5" ? 'selected' : "" }}>Dead body management work</option>
                                        <option value="6" {{$data && $data->high_exposure == "6" ? 'selected' : "" }}>Old Age Home / Care work</option>
                                        <option value="7" {{$data && $data->high_exposure == "7" ? 'selected' : "" }}>Border Crossing / Point of Entry Staff</option>
                                        <option value="8" {{$data && $data->high_exposure == "8" ? 'selected' : "" }}>Any Security Staff</option>
                                        <option value="9" {{$data && $data->high_exposure == "9" ? 'selected' : "" }}>Hotel/Restaurant/Bar work</option>
                                        <option value="10" {{$data && $data->high_exposure == "10" ? 'selected' : "" }}>Farm work</option>
                                        <option value="11" {{$data && $data->high_exposure == "11" ? 'selected' : "" }}>Shop/Store worker</option>
                                        <option value="12" {{$data && $data->high_exposure == "12" ? 'selected' : "" }}>Journalist</option>
                                        <option value="13" {{$data && $data->high_exposure == "13" ? 'selected' : "" }}>Migrant</option>
                                        <option value="14" {{$data && $data->high_exposure == "14" ? 'selected' : "" }}>Refugee</option>
                                        <option value="15" {{$data && $data->high_exposure == "15" ? 'selected' : "" }}>Prisoner</option>
                                        <option value="16" {{$data && $data->high_exposure == "16" ? 'selected' : "" }}>Teacher</option>
                                        <option value="17" {{$data && $data->high_exposure == "17" ? 'selected' : "" }}>Student</option>
                                        <option value="18" {{$data && $data->high_exposure == "18" ? 'selected' : "" }}>Local body Elected Representative</option>
                                        <option value="19" {{$data && $data->high_exposure == "19" ? 'selected' : "" }}>Bank/Govt Office / Public Corporation staff</option>
                                        <option value="20" {{$data && $data->high_exposure == "20" ? 'selected' : "" }}>UN / Development Partner / INGO / NGO (Frontline worker)</option>
                                        <option value="0" {{$data && $data->high_exposure == "0" ? 'selected' : "" }}>Others</option>
                                    </select>
                                    <br>
                                    
                                    <div class="form-group high_exposure_other_class {{ $errors->has('high_exposure_other') ? 'has-error' : '' }}">
                                        <label for="high_exposure_other">If others, specify</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->high_exposure_other : '' }}" name="high_exposure_other"
                                               aria-describedby="help" placeholder="Enter other high exposure"
                                        >
                                        @if ($errors->has('high_exposure_other'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('high_exposure_other') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="part-four">
                                <h4>IV. Travel during 14 days before OR after symptom onset or date of sample collection for testing</h4><br>

                                <div class="form-group">
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
                                
                                <div class="form-group travelled_14_days_yes_class">
                                    <label class="control-label" for="caste">Fill in the table below both for foreign and domestic travel in the relevant columns of the table</label><br>
                                    <button type="button" class="btn btn-success btn-sm btn-add-fourteen-days" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Place of Departure from</th>
                                                    <th>Place of arrival to</th>
                                                    <th>Date of Departure from or to the place</th>
                                                    <th>Date of Arrival in Nepal or Current place of Residence</th>
                                                    <th>Mode of travel</th>
                                                    <th>Other Mode of travel</th>
                                                    <th>Flight / Vehicle No. / Bus Route / Driver Contact No.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-fourteen-days-tbody text-center">
                                                @if($data->travelled_14_days_details && $data->travelled_14_days_details != null && $data->travelled_14_days_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->travelled_14_days_details);
                                                ?>
                                                @foreach($sub_data_array as $key => $sub_data)
                                                <tr class="table-fourteen-days-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-fourteen-days mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_departure_from[]" value="{{ $sub_data->departure_from }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_arrival_to[]" value="{{ $sub_data->arrival_to }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="departure_date_{{ $key }}" class="form-control nep-date-departure" name="travelled_14_days_details_departure_date_np[]" value="{{ $sub_data->departure_date }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="arrival_date_{{ $key }}" class="form-control nep-date-arrival" name="travelled_14_days_details_arrival_date_np[]" value="{{ $sub_data->arrival_date }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control travelled_14_days_details_travel_mode" name="travelled_14_days_details_travel_mode[]">
                                                            <option value=""  {{ $sub_data->travel_mode == '' ? 'selected' : '' }}>Select Mode of travel</option>
                                                            <option class="form-control" value="1" {{ $sub_data->travel_mode == '1' ? 'selected' : '' }}>Air</option>
                                                            <option class="form-control" value="2" {{ $sub_data->travel_mode == '2' ? 'selected' : '' }}>Public Transport</option>
                                                            <option class="form-control" value="3" {{ $sub_data->travel_mode == '3' ? 'selected' : '' }}>Private Vehicle</option>
                                                            <option class="form-control" value="0" {{ $sub_data->travel_mode == '0' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control travelled_14_days_details_travel_mode_other" name="travelled_14_days_details_travel_mode_other[]" value="{{ $sub_data->travel_mode_other }}" @if( $sub_data->travel_mode != '0') readonly @endif>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_vehicle_no[]" value="{{ $sub_data->vehicle_no }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-fourteen-days-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-fourteen-days mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_departure_from[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_arrival_to[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control nep-date-departure" name="travelled_14_days_details_departure_date_np[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control nep-date-arrival" name="travelled_14_days_details_arrival_date_np[]">
                                                    </td>
                                                    <td>
                                                        <select class="form-control travelled_14_days_details_travel_mode" name="travelled_14_days_details_travel_mode[]">
                                                            <option value="" selected>Select Mode of travel</option>
                                                            <option class="form-control" value="1">Air</option>
                                                            <option class="form-control" value="2">Public Transport</option>
                                                            <option class="form-control" value="3">Private Vehicle</option>
                                                            <option class="form-control" value="0">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control travelled_14_days_details_travel_mode_other" name="travelled_14_days_details_travel_mode_other[]" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travelled_14_days_details_vehicle_no[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="part-five">
                                <h4>V. Information on <b>Source of Exposure of Case</b> under investigation</h4><br>
                    
                                <div class="form-group">
                                    <label class="control-label">Identify the following categories of persons whom the case might have contracted the infection from, upto 14 days before the development of the symptoms OR 24 days prior to the date of sample collection in case of asymptomatic</label>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="reference_period_for">Reference Period From</label>
                                    <input type="text" class="form-control" value="{{ $data ? $data->exposure_ref_period_from_np : "" }}" name="exposure_ref_period_from_np" id="exposure_ref_period_from_np"
                                            aria-describedby="help" placeholder="Enter Reference Period From">
                                    @if ($errors->has('exposure_ref_period_from_np'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('exposure_ref_period_from_np') }}</small>
                                    @endif
    
                                    <label for="reference_period_to">Reference Period To</label>
                                    <input type="text" class="form-control" value="{{ $data ? $data->exposure_ref_period_to_np : '' }}" name="exposure_ref_period_to_np" id="exposure_ref_period_to_np"
                                            aria-describedby="help" placeholder="Enter Reference Period To">
                                    @if ($errors->has('exposure_ref_period_to_np'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('exposure_ref_period_to_np') }}</small>
                                    @endif

                                    <input type="hidden" id="exposure_ref_period_from_np_bak" value="{{ $data ? $data->exposure_ref_period_from_np : "" }}">
                                    <input type="hidden" id="exposure_ref_period_to_np_bak" value="{{ $data ? $data->exposure_ref_period_to_np : "" }}">
                                </div>

                                <hr>    
                            
                                <div class="form-group">
                                    <label>Did any known case(s) of COVID-19 live in the same household as the case under investigation during the reference period?</label><br>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="same_household" class="same_household"
                                                    {{ $data && $data->same_household == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="same_household" class="same_household"
                                                    {{ $data && $data->same_household == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="same_household" class="same_household"
                                                    {{ $data && $data->same_household == "2" ? 'checked' : '' }} value="2">Unknown
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group same_household_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-same-household" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name of source</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-same-household-tbody text-center">

                                                @if($data->same_household_details && $data->same_household_details != null && $data->same_household_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->same_household_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-same-household-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-same-household mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="same_household_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="same_household_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_phone[]" value="{{ $sub_data->phone }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-same-household-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-same-household mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="same_household_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="same_household_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="same_household_details_phone[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <hr>
    
                                <div class="form-group">
                                    <label>Did the case had close contact with probable and confirmed case/person with travel history form COVID-19 affected place during the reference period?</label><br>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="close_contact" class="close_contact"
                                                    {{ $data && $data->close_contact == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="close_contact" class="close_contact"
                                                    {{ $data && $data->close_contact == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="close_contact" class="close_contact"
                                                    {{ $data && $data->close_contact == "2" ? 'checked' : '' }} value="2">Unknown
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group close_contact_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-close-contact" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name of source</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-close-contact-tbody text-center">
                                                @if($data->close_contact_details && $data->close_contact_details != null && $data->close_contact_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->close_contact_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-close-contact-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-close-contact mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="close_contact_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="close_contact_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_phone[]" value="{{ $sub_data->phone }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-close-contact-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-close-contact mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="close_contact_details_age_unit[]" class="form-control">
                                                            <option value="" selected>Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="close_contact_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="close_contact_details_phone[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <hr>
    
                                <div class="form-group">
                                    <label>Did the case under investigation provide direct care to known case(s) of COVID-19 during the reference period?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="direct_care" class="direct_care"
                                                {{ $data && $data->direct_care == "0" ? 'checked' : '' }} value="0">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="direct_care" class="direct_care"
                                                {{ $data && $data->direct_care == "1" ? 'checked' : '' }} value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="direct_care" class="direct_care"
                                                {{ $data && $data->direct_care == "2" ? 'checked' : '' }} value="2">Unknown
                                    </label>
                                </div>

                                <div class="form-group direct_care_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-direct-care" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name of source</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-direct-care-tbody text-center">
                                                @if($data->direct_care_details && $data->direct_care_details != null && $data->direct_care_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->direct_care_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-direct-care-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-direct-care mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="direct_care_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="direct_care_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_phone[]" value="{{ $sub_data->phone }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-direct-care-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-direct-care mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="direct_care_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="direct_care_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="direct_care_details_phone[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <hr>
    
                                <div class="form-group">
                                    <label>Did the case under investigation attend School / Workplace / Hospitals / Healthcare institution / Social gathering(s) during the reference period?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="attend_social" class="attend_social"
                                                {{ $data && $data->attend_social == "0" ? 'checked' : '' }} value="0">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="attend_social" class="attend_social"
                                                {{ $data && $data->attend_social == "1" ? 'checked' : '' }} value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="attend_social" class="attend_social"
                                                {{ $data && $data->attend_social == "2" ? 'checked' : '' }} value="2">Unknown
                                    </label>
                                </div>

                                <div class="form-group attend_social_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-school-attend" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name of School / Workplace /Social gathering Venue & Address</th>
                                                    <th>Number of Close Contacts & Details</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-school-attend-tbody text-center">
                                                @if($data->attend_social_details && $data->attend_social_details != null && $data->attend_social_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->attend_social_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-school-attend-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-school-attend mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_phone[]" value="{{ $sub_data->phone }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_remarks[]" value="{{ $sub_data->remarks }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-school-attend-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-school-attend mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_phone[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="attend_social_details_remarks[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block ">SAVE AND CONTINUE</button>
                            
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
    
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#date_of_onset_of_first_symptom').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate,
        onChange: function(){
            var en_11_date_final = getFourteenDays($('#date_of_onset_of_first_symptom').val());
            $('#exposure_ref_period_from_np').val(en_11_date_final);
            $('#exposure_ref_period_to_np').val($('#date_of_onset_of_first_symptom').val());
        }
    });

    // $('.nep-date-departure .nep-date-arrival').nepaliDatePicker({
    //     language: 'english',
    //     disableAfter: currentDate
    // });
    
    function getFourteenDays(cur_date){
        var np_date_obj = NepaliFunctions.ConvertToDateObject($('#date_of_onset_of_first_symptom').val(), "YYYY-MM-DD");
        var en_date_obj = NepaliFunctions.BS2AD(np_date_obj);
        var en_date = NepaliFunctions.ConvertDateFormat(en_date_obj, "YYYY-MM-DD");
        var en_date = new Date(en_date);
        en_date.setDate(en_date.getDate() - 14);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }

    function collectionDate(sel_date){
        var en_date = new Date(sel_date);
        en_date.setDate(en_date.getDate() - 24);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }

    function currentNepaliDate(sel_date){
        var en_date = new Date(sel_date);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }

    $('#exposure_ref_period_from_np').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });

    $('#exposure_ref_period_to_np').nepaliDatePicker({
        language: 'english'
    });

    symptomsTwoWeeks();
    $('.symptoms_recent').on('change', function() {
        symptomsTwoWeeks();
    });
    function symptomsTwoWeeks(){
        if($('.symptoms_recent:checked').val() == '0'){
            $('.symptoms_two_weeks_class').show();
            $('#symptoms_two_weeks').prop('required', true);
            $('.is-symptomatic').hide();
            $('#date_of_onset_of_first_symptom').prop('required', false);
            $('#date_of_onset_of_first_symptom').val("");
            $('#symptoms_specific').val("");

            if($('#exposure_ref_period_from_np_bak').val() == ''){
                $('#exposure_ref_period_from_np').val(collectionDate($('#sample_collection_date').val()));
            }
            if($('#exposure_ref_period_to_np_bak').val() == ''){
                $('#exposure_ref_period_to_np').val(currentNepaliDate($('#sample_collection_date').val()));
            }
        }
        else {
            $('.symptoms_two_weeks_class').hide();
            $('#symptoms_two_weeks').prop('required', false);
            $('.is-symptomatic').show();
            $('#date_of_onset_of_first_symptom').prop('required', true);

        }
    }
    $('.symptoms_two_weeks').on('change', function() {
        if($('.symptoms_two_weeks:checked').val() == '1'){
            $('.is-symptomatic').show();
            $('#date_of_onset_of_first_symptom').prop('required', true);
        }else {
            symptomsTwoWeeks();
        }
    });

    high_exposure();
    $('.high_exposure').on('change', function() {
        high_exposure();
    });
    function high_exposure(){
        if($('.high_exposure').val() == '0'){
            $('.high_exposure_other_class').show();
        }else {
            $('.high_exposure_other_class').hide();
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

    same_household();
    $('.same_household').on('change', function() {
        same_household();
    });
    function same_household(){
        if($('.same_household:checked').val() == '1'){
            $('.same_household_yes_class').show();
        }else {
            $('.same_household_yes_class').hide();
        }
    }

    close_contact();
    $('.close_contact').on('change', function() {
        close_contact();
    });
    function close_contact(){
        if($('.close_contact:checked').val() == '1'){
            $('.close_contact_yes_class').show();
        }else {
            $('.close_contact_yes_class').hide();
        }
    }

    direct_care();
    $('.direct_care').on('change', function() {
        direct_care();
    });
    function direct_care(){
        if($('.direct_care:checked').val() == '1'){
            $('.direct_care_yes_class').show();
        }else {
            $('.direct_care_yes_class').hide();
        }
    }

    attend_social();
    $('.attend_social').on('change', function() {
        attend_social();
    });
    function attend_social(){
        if($('.attend_social:checked').val() == '1'){
            $('.attend_social_yes_class').show();
        }else {
            $('.attend_social_yes_class').hide();
        }
    }



    $(document).on('change', '.travelled_14_days_details_travel_mode', function() {
        if ($(this).val() == 0) {
            $(this).parent().next().find("[readonly]").prop('readonly', false);
        } else {
            $(this).parent().next().find(".travelled_14_days_details_travel_mode_other").val("");
            $(this).parent().next().find(".travelled_14_days_details_travel_mode_other").prop('readonly', true);
        }
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
            disableAfter: currentDate,
            onChange: function(e) {
                $('#arrival_date_' + random).nepaliDatePicker({
                    language: 'english',
                    disableBefore: $('#departure_date_' + random).val(),
                    disableAfter: currentDate
                });
                $('#arrival_date_' + random).prop('readonly', false);
            }
		});
        new_row.find(".nep-date-arrival").attr('id', 'arrival_date_' + random).prop('readonly', true);
        new_row.find(".travelled_14_days_details_travel_mode_other").prop('readonly', true);
	});

    $('body').on('click', '.btn-remove-fourteen-days', function() {
        if(!confirm('Are you sure you want to delete?')){
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
    });

    $('body').on('click', '.btn-remove-same-household', function() {
        if(!confirm('Are you sure you want to delete?')){
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
    });

    $('body').on('click', '.btn-remove-close-contact', function() {
        if(!confirm('Are you sure you want to delete?')){
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
    });

    $('body').on('click', '.btn-remove-direct-care', function() {
        if(!confirm('Are you sure you want to delete?')){
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
    });

    $('body').on('click', '.btn-remove-school-attend', function() {
        if(!confirm('Are you sure you want to delete?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-school-attend-tr").remove();
    });

    $(function () {
        $.validator.addMethod("nameCustom", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
        }, "Name is invalid: Please enter a valid name.");

        $.validator.addMethod("ageCustom", function (value, element) {
            return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
        }, "Age is invalid: Please enter a valid age.");

        $.validator.addMethod("phoneCustom", function (value, element) {
            return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
        }, "Contact number is invalid: Please enter a valid phone number.");
        $("form[name='createCase']").validate({
            // Define validation rules
            rules: {
                symptoms_recent: {
                    required: true
                },
                high_exposure: {
                    required: true
                },
                high_exposure_other: {
                    maxlength: 50,
                },
                "travelled_14_days_details_departure_from[]": {
                    required: function () {
                        return $(".travelled_14_days:checked").val() == "1";
                    }
                },
                "travelled_14_days_details_arrival_to[]": {
                    required: function () {
                        return $(".travelled_14_days:checked").val() == "1";
                    }
                },
                "travelled_14_days_details_departure_date_np[]": {
                    required: function () {
                        return $(".travelled_14_days:checked").val() == "1";
                    }
                },
                "travelled_14_days_details_arrival_date_np[]": {
                    required: function () {
                        return $(".travelled_14_days:checked").val() == "1";
                    }
                },
                "travelled_14_days_details_travel_mode[]": {
                    required: function () {
                        return $(".travelled_14_days:checked").val() == "1";
                    }
                },
                exposure_ref_period_from_np: {
                    required: true
                },
                exposure_ref_period_to_np: {
                    required: true
                },
                same_household: {
                    required: true
                },
                "same_household_details_name[]": {
                    nameCustom: true
                },
                "same_household_details_age[]": {
                    digits: true,
                    maxlength: 3,
                },
                "same_household_details_phone[]": {
                    digits: true,
                    minlength: 7,
                    maxlength: 10,
                },
                close_contact: {
                    required: true
                },
                "close_contact_details_name[]": {
                    nameCustom: true
                },
                "close_contact_details_age[]": {
                    digits: true,
                    maxlength: 3,
                },
                "close_contact_details_phone[]": {
                    digits: true,
                    minlength: 7,
                    maxlength: 10,
                },
                direct_care: {
                    required: true
                },
                "direct_care_details_name[]": {
                    nameCustom: true
                },
                "direct_care_details_age[]": {
                    digits: true,
                    maxlength: 3,
                },
                "direct_care_details_phone[]": {
                    digits: true,
                    minlength: 7,
                    maxlength: 10,
                },
                attend_social: {
                    required: true
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