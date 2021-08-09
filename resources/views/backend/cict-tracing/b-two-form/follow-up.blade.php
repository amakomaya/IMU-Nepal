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
                        <strong>Contact Follow Up Form (B2 Form)</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('b-two-form.update', $data->case_id), ['name' => 'createCase']) !!}
                        {{ method_field('PUT') }}
                        <div class="panel-body">
                            <label class="control-label"><h4>Case information</h4></label>
                            
                            <div class="form-group">
                                <label for="name">Name of the Case: {{ $cict_tracing ? $cict_tracing->name : '' }}</label><br>
                                <label for="name">EPI ID: {{ $cict_tracing ? $cict_tracing->case_id : '' }}</label><br>
                            </div>

                            <hr>
                            
                            <label class="control-label"><h4>Contact information</h4></label>
                            <div class="form-group">
                                <label for="name">Name: {{ $cict_contact ? $cict_contact->name : '' }}</label><br>
                                <label for="name">EPI ID: {{ $cict_contact ? $cict_contact->case_id : '' }}</label><br>
                            </div>

                            <div class="form-group sars_cov2_vaccinated_yes_class">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="background: #fff;">
                                            <tr>
                                                <th rowspan="2" width="5%">Days since last contact with the case</th>
                                                <th rowspan="2" width="5%">Days to follow up</th>
                                                <th rowspan="2" width="30%">Date of follow up</th>
                                                <th colspan="7" width="57%">Symptoms</th>
                                                <th rowspan="2" width="3%"></th>
                                            </tr>
                                            <tr>
                                                <th>No Symptoms</th>
                                                <th>Fever ≥38 °C</th>
                                                <th>Runny nose</th>
                                                <th>Cough</th>
                                                <th>Sorethroat</th>
                                                <th>Shortness of breath</th>
                                                <th>Other symptoms: specify</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-sars-cov-tbody text-center">
                                            <?php 
                                            for($i=0; $i<11; $i++){
                                            ?>

                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ 10 - $i }}</td>
                                                <td>
                                                    <input type="text" class="form-control date_of_follow_up" data-id="{{ $i }}" name="date_of_follow_up_{{$i}}" id="date_of_follow_up_{{$i}}" value="{{ isset($data->{'date_of_follow_up_'.$i}) ? $data->{'date_of_follow_up_'.$i} : '' }}">
                                                </td>
                                                <td>
                                                    <input type="checkbox" class="no_symptoms" data-id="{{ $i }}" id="no_symptoms_{{$i}}" value="1" name="no_symptoms_{{$i}}" {{ isset($data->{'no_symptoms_'.$i}) && $data->{'no_symptoms_'.$i} == 1 ? 'checked' : '' }}> None
                                                </td>
                                                <td>
                                                    <input type="radio" class="fever_{{$i}}" value="1" name="fever_{{$i}}" {{ isset($data->{'fever_'.$i}) && $data->{'fever_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" class="fever_{{$i}}" value="0" name="fever_{{$i}}" {{ isset($data->{'fever_'.$i}) && $data->{'fever_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" class="runny_nose_{{$i}}" value="1" name="runny_nose_{{$i}}" {{ isset($data->{'runny_nose_'.$i}) && $data->{'runny_nose_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" class="runny_nose_{{$i}}" value="0" name="runny_nose_{{$i}}" {{ isset($data->{'runny_nose_'.$i}) && $data->{'runny_nose_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" class="cough_{{$i}}" value="1" name="cough_{{$i}}" {{ isset($data->{'cough_'.$i}) && $data->{'cough_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" class="cough_{{$i}}" value="0" name="cough_{{$i}}" {{ isset($data->{'cough_'.$i}) && $data->{'cough_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" class="sore_throat_{{$i}}" value="1" name="sore_throat_{{$i}}" {{ isset($data->{'sore_throat_'.$i}) && $data->{'sore_throat_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" class="sore_throat_{{$i}}" value="0" name="sore_throat_{{$i}}" {{ isset($data->{'sore_throat_'.$i}) && $data->{'sore_throat_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" class="breath_{{$i}}" value="1" name="breath_{{$i}}" {{ isset($data->{'breath_'.$i}) && $data->{'breath_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" class="breath_{{$i}}" value="0" name="breath_{{$i}}" {{ isset($data->{'breath_'.$i}) && $data->{'breath_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control symptoms_other_{{$i}}" name="symptoms_other_{{$i}}" id="symptoms_other_{{$i}}" value="{{ isset($data->{'symptoms_other_'.$i}) ?? '' }}">
                                                </td>
                                                @if($i == 5)
                                                <td style="background-color: red; color:white">
                                                    PCR/Antigen Test
                                                </td>
                                                @else
                                                <td></td>
                                                @endif
                                            </tr>
                                            <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <p>* Follow-up should start from the day it has been since last contact with the case. For e.g., if the contact has not been in contact with the case
                                    since 12 days, the follow-up should start from the 12th day in the column “Days to follow up”
                                </p>
                                <p>** Please select None for No symptoms. If no symptoms are experienced, then consider the entry comple</p>
                            </div>

                            <label class="control-label"><h4>Final Contact Classification at final follow-up</h4></label>
                            <div class="form-group">
                                <select name="high_exposure" class="form-control high_exposure">
                                    <option value="" {{ isset($data->high_exposure) && $data->high_exposure == "" ? 'selected' : "" }}>Select Final Contact Classification at final follow-up</option>
                                    <option value="1" {{ isset($data->high_exposure) && $data->high_exposure == "1" ? 'selected' : "" }}>Never ill/not a case</option>
                                    <option value="2" {{ isset($data->high_exposure) && $data->high_exposure == "2" ? 'selected' : "" }}>Confirmed Secondary Case</option>
                                    <option value="3" {{ isset($data->high_exposure) && $data->high_exposure == "3" ? 'selected' : "" }}>Lost to follow-up</option>
                                    <option value="4" {{ isset($data->high_exposure) && $data->high_exposure == "4" ? 'selected' : "" }}>Suspected Case</option>
                                    <option value="5" {{ isset($data->high_exposure) && $data->high_exposure == "5" ? 'selected' : "" }}>Probable Case</option>
                                    <option value="6" {{ isset($data->high_exposure) && $data->high_exposure == "6" ? 'selected' : "" }}>Death</option>
                                </select>
                            </div>

                            <div class="part-four">
                                <h4>Data Collector information</h4><br>

                                <div class="form-group">
                                    @if(isset($data->checkedBy))
                                    <b>Name:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->name : '' }}<br>
                                    <b>Telephone Number:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->phone : '' }}<br>
                                    <b>Instituton:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->getHealthpost($data->hp_code) : '' }}<br>
                                    <b>Email:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->user->email : '' }}<br>
                                    @else
                                    <b>Name:</b> {{ isset($cict_contact->checkedBy) && $cict_contact->checkedBy ? $cict_contact->checkedBy->name : '' }}<br>
                                    <b>Telephone Number:</b> {{ isset($cict_contact->checkedBy) && $cict_contact->checkedBy ? $cict_contact->checkedBy->phone : '' }}<br>
                                    <b>Instituton:</b> {{ isset($cict_contact->checkedBy) && $cict_contact->checkedBy ? $cict_contact->checkedBy->getHealthpost($cict_contact->hp_code) : '' }}<br>
                                    <b>Email:</b> {{ isset($cict_contact->checkedBy) && $cict_contact->checkedBy ? $cict_contact->checkedBy->user->email : '' }}<br>
                                    @endif
                                    <label>Form Completion Date </label><br>
                                    <input type="text" class="form-control" value="{{ isset($data->completion_date) ? $data->completion_date : '' }}" name="completion_date"
                                            aria-describedby="help" placeholder="Enter Form Completion Date" id="completion_date"
                                    >
                                    @if ($errors->has('completion_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('completion_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="case_id" value={{ $data->case_id }}>
                            <input type="hidden" name="parent_case_id" value={{ $data->parent_case_id }}>

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
        var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
        $('#completion_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });

        $('.date_of_follow_up').each(function() {
            var $this = $(this);
            $this.on("click", function () {
                var id = $(this).data('id');
                if(id > 0){
                    if($('#date_of_follow_up_' + (id-1)).val() != ''){
                        $("#date_of_follow_up_" + id).nepaliDatePicker({
                            language: 'english',
                            disableBefore: getOneDayAfter($('#date_of_follow_up_' + (id-1)).val()),
                            disableAfter: currentDate,
                            onChange: function() {
                                if(id < 10){
                                    dateSetting(id)
                                }
                            }
                        });
                    }else {
                        $('#date_of_follow_up_' + id).nepaliDatePicker({
                            language: 'english',
                            disableAfter: currentDate,
                            onChange: function() {
                                if(id < 10){
                                    dateSetting(id)
                                }
                            }
                        });

                    }
                }else{
                    $('#date_of_follow_up_' + id).nepaliDatePicker({
                        language: 'english',
                        disableAfter: currentDate,
                        onChange: function() {
                            dateSetting(id)
                        }
                    });

                }
            })
        });

        function dateSetting(id){
            $('#date_of_follow_up_' + (id+1)).nepaliDatePicker({
                language: 'english',
                disableAfter: currentDate,
                disableBefore: getOneDayAfter($('#date_of_follow_up_' + id).val()),
            })
        }
        
        var i;
        for (i = 0; i < 11; ++i) {
            if(i > 0){
                if($('#date_of_follow_up_' + (i-1)).val() != ''){
                    $("#date_of_follow_up_" + i).nepaliDatePicker({
                        language: 'english',
                        disableBefore: getOneDayAfter($('#date_of_follow_up_' + (i-1)).val()),
                        disableAfter: currentDate
                    });
                }else {
                    $("#date_of_follow_up_" + i).nepaliDatePicker({
                        language: 'english',
                        disableAfter: currentDate
                    });
                }
            }

            if ($("#no_symptoms_" + i).is(':checked')){
                $('input[name=fever_' + i + ']').prop('checked', false).prop("disabled",true);
                $('input[name=runny_nose_' + i + ']').prop('checked', false).prop("disabled",true);
                $('input[name=cough_' + i + ']').prop('checked', false).prop("disabled",true);
                $('input[name=sore_throat_' + i + ']').prop('checked', false).prop("disabled",true);
                $('input[name=breath_' + i + ']').prop('checked', false).prop("disabled",true);
                $('input[name=symptoms_other_' + i + ']').val("").prop("readonly",true);
            }
        }
    
        function getOneDayAfter(cur_date){
            var np_date_obj = NepaliFunctions.ConvertToDateObject(cur_date, "YYYY-MM-DD");
            var en_date_obj = NepaliFunctions.BS2AD(np_date_obj);
            var en_date = NepaliFunctions.ConvertDateFormat(en_date_obj, "YYYY-MM-DD");
            var en_date = new Date(en_date);
            en_date.setDate(en_date.getDate() + 1);
            var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
            var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
            en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
            var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
            return en_11_date_final;
        }
        
        $('.no_symptoms').each(function() {
            var $this = $(this);
            $this.on("click", function () {
                var id = $(this).data('id');
                if(this.checked) {
                    $('input[name=fever_' + id + ']').prop('checked', false).prop("disabled",true);
                    $('input[name=runny_nose_' + id + ']').prop('checked', false).prop("disabled",true);
                    $('input[name=cough_' + id + ']').prop('checked', false).prop("disabled",true);
                    $('input[name=sore_throat_' + id + ']').prop('checked', false).prop("disabled",true);
                    $('input[name=breath_' + id + ']').prop('checked', false).prop("disabled",true);
                    $('input[name=symptoms_other_' + id + ']').val("").prop("readonly",true);
                }else {
                    $('input[name=fever_' + id + ']').prop("disabled",false);
                    $('input[name=runny_nose_' + id + ']').prop("disabled",false);
                    $('input[name=cough_' + id + ']').prop("disabled",false);
                    $('input[name=sore_throat_' + id + ']').prop("disabled",false);
                    $('input[name=breath_' + id + ']').prop("disabled",false);
                    $('input[name=symptoms_other_' + id + ']').prop("readonly",false);
                }
            });
            
        });
    </script>
@endsection