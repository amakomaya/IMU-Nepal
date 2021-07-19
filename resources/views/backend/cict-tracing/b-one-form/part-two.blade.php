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
                        <strong>CICT Form Part 2/3</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('cict-tracing.section-two.update'), ['name' => 'createCase']) !!}
                        <div class="panel-body">

                            <div class="part-one">
                                <h4>I. Contacts Clinical Informations</h4><br>

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
                                                {{ $data && $data->symptoms_two_weeks == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="symptoms_two_weeks" class="symptoms_two_weeks"
                                                {{ $data && $data->symptoms_two_weeks == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="is-symptomatic">
                                    <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom_np') ? 'has-error' : '' }}">
                                        <label for="date_of_onset_of_first_symptom_np">Date of onset of first symptom:</label>
                                        <input type="text" class="form-control" id="date_of_onset_of_first_symptom_np"
                                            name="date_of_onset_of_first_symptom_np" value="{{ $data ? $data->date_of_onset_of_first_symptom : '' }}" aria-describedby="help">
                                        @if ($errors->has('date_of_onset_of_first_symptom_np'))
                                            <small id="help"
                                                class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom_np') }}</small>
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
                                        <input type="text" class="form-control" value="{{ $data ? $data->symptoms_specific : '' }}" name="symptoms_specific"
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
                                    <input type="text" class="form-control" value="{{ $data ? $data->symptoms_comorbidity_specific : '' }}" name="symptoms_comorbidity_specific"
                                           aria-describedby="help" placeholder="Enter other symptoms"
                                    >
                                    @if ($errors->has('symptoms_comorbidity_specific'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <h4>III. Occupation</h4><br>
                                <select name="occupation" class="form-control">
                                    <option {{ old('occupation') == '' ? "selected" : "" }} value="">Select Occupation
                                    </option>
                                    <option {{ old('occupation') == '1' ? "selected" : "" }} value="1">Health care worker
                                    </option>
                                    <option {{ old('occupation') == '14' ? "selected" : "" }} value="14">Working with animals</option>
                                    <option {{ old('occupation') == '6' ? "selected" : "" }} value="6">Student/Teacher</option>
                                    <option {{ old('occupation') == '16' ? "selected" : "" }} value="16">Security Personnel
                                    </option>
                                    <option {{ old('occupation') == '17' ? "selected" : "" }} value="17">Waste Management Worker
                                    </option>
                                    <option {{ old('occupation') == '18' ? "selected" : "" }} value="18">Hotel/Restaurants/Bars
                                    </option>
                                    <option {{ old('occupation') == '19' ? "selected" : "" }} value="19">Bank and Finance worker
                                    </option>
                                    <option {{ old('occupation') == '11' ? "selected" : "" }} value="11">Others</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
    
                            <div class="form-group {{ $errors->has('occupaton_other') ? 'has-error' : '' }}">
                                <label for="occupaton_other">If other, specify</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->occupaton_other : '' }}" name="occupaton_other"
                                       aria-describedby="help" placeholder="Enter other symptoms"
                                >
                                @if ($errors->has('occupaton_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('occupaton_other') }}</small>
                                @endif
                            </div>
    
                            <div class="form-group {{ $errors->has('healthworker_title') ? 'has-error' : '' }}">
                                <label for="healthworker_title">Job Title</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->healthworker_title : '' }}" name="healthworker_title"
                                       aria-describedby="help" placeholder="Enter other symptoms"
                                >
                                @if ($errors->has('healthworker_title'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_title') }}</small>
                                @endif

                                <label for="healthworker_workplace">Name of the work place</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->healthworker_workplace : '' }}" name="healthworker_workplace"
                                       aria-describedby="help" placeholder="Enter other symptoms"
                                >
                                @if ($errors->has('healthworker_workplace'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_workplace') }}</small>
                                @endif

                                <label>Station</label><br>

                                <select name="healthworker_station" class="form-control">
                                    <option {{ old('healthworker_station') == '' ? "selected" : "" }} value="">Select healthworker_station
                                    </option>
                                    <option {{ old('healthworker_station') == '1' ? "selected" : "" }} value="1">Fever Clinic
                                    </option>
                                    <option {{ old('healthworker_station') == '2' ? "selected" : "" }} value="2">Isolation Ward</option>
                                    <option {{ old('healthworker_station') == '3' ? "selected" : "" }} value="3">ICU/Ventilator</option>
                                    <option {{ old('healthworker_station') == '4' ? "selected" : "" }} value="4">Lab
                                    <option {{ old('healthworker_station') == '0' ? "selected" : "" }} value="0">Others</option>
                                </select>
                                @if ($errors->has('healthworker_station'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('healthworker_station') }}</small>
                                @endif

                                <label for="healthworker_station_other">If others, specfiy</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->healthworker_station_other : '' }}" name="healthworker_station_other"
                                       aria-describedby="help" placeholder="Enter other station"
                                >
                                @if ($errors->has('healthworker_station_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_station_other') }}</small>
                                @endif

                            </div>


                            <div class="part-three">
                                <h4>III. High exposure category of Case under Investigation belongs to</h4><br>

                                <div class="form-group">
                                    <select name="high_exposure" class="form-control high_exposure">
                                        <option value="" disabled selected>Select High Exposure</option>
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
                                        <option value="20" {{$data && $data->high_exposure == "20" ? 'selected' : "" }}>UN / Development Partner / INGO / NGO Frontline worker</option>
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

                                    <div class="form-group">
                                        <label class="control-label">Was appropriate PPE used?</label>
                                        <div class="control-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_ppe" class="healthworker_ppe"
                                                    {{ $data && $data->healthworker_ppe == "1" ? 'checked' : '' }} value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="healthworker_ppe" class="healthworker_ppe"
                                                    {{ $data && $data->healthworker_ppe == "0" ? 'checked' : '' }} value="0">No
                                            </label>
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="healthworker_first_date">Date of first contact</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->healthworker_first_date : '' }}" name="healthworker_first_date"
                                               aria-describedby="help" placeholder="Enter other symptoms"
                                        >
                                        @if ($errors->has('healthworker_first_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_first_date') }}</small>
                                        @endif

                                        <label for="healthworker_last_date">Date of last contact</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->healthworker_last_date : '' }}" name="healthworker_last_date"
                                               aria-describedby="help" placeholder="Enter other symptoms"
                                        >
                                        @if ($errors->has('healthworker_last_date'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_last_date') }}</small>
                                        @endif

                                        <label for="healthworker_narrative">Any relevant narrative</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->healthworker_narrative : '' }}" name="healthworker_narrative"
                                               aria-describedby="help" placeholder="Enter other symptoms"
                                        >
                                        @if ($errors->has('healthworker_narrative'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('healthworker_narrative') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="part-four">
                                <h4>IV. Travel during 14 days before OR aftersymptom onset or date of sample collection for testing</h4><br>

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