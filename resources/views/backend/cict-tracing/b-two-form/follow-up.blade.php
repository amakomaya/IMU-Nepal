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
                                                <th rowspan="2">Days since last contact with the case</th>
                                                <th rowspan="2">Days to follow up</th>
                                                <th rowspan="2">Date of follow up</th>
                                                <th colspan="7">Symptoms</th>
                                                <th rowspan="2"></th>
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
                                                    <input type="text" class="form-control" name="date_of_follow_up_{{$i}}" id="date_of_follow_up_{{$i}}" value="{{ isset($data->{'date_of_follow_up_'.$i}) ? $data->{'date_of_follow_up_'.$i} : '' }}">
                                                </td>
                                                <td>
                                                    <input type="checkbox" value="1" name="no_symptoms_{{$i}}" {{ isset($data->{'no_symptoms_'.$i}) && $data->{'no_symptoms_'.$i} == 1 ? 'checked' : '' }}> None
                                                </td>
                                                <td>
                                                    <input type="radio" value="1" name="fever_{{$i}}" {{ isset($data->{'fever_'.$i}) && $data->{'fever_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" value="0" name="fever_{{$i}}" {{ isset($data->{'fever_'.$i}) && $data->{'fever_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" value="1" name="runny_nose_{{$i}}" {{ isset($data->{'runny_nose_'.$i}) && $data->{'runny_nose_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" value="0" name="runny_nose_{{$i}}" {{ isset($data->{'runny_nose_'.$i}) && $data->{'runny_nose_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" value="1" name="cough_{{$i}}" {{ isset($data->{'cough_'.$i}) && $data->{'cough_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" value="0" name="cough_{{$i}}" {{ isset($data->{'cough_'.$i}) && $data->{'cough_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" value="1" name="sore_throat_{{$i}}" {{ isset($data->{'sore_throat_'.$i}) && $data->{'sore_throat_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" value="0" name="sore_throat_{{$i}}" {{ isset($data->{'sore_throat_'.$i}) && $data->{'sore_throat_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="radio" value="1" name="breath_{{$i}}" {{ isset($data->{'breath_'.$i}) && $data->{'breath_'.$i} == 1 ? 'checked' : '' }}> Yes<br>
                                                    <input type="radio" value="0" name="breath_{{$i}}" {{ isset($data->{'breath_'.$i}) && $data->{'breath_'.$i} == 0 ? 'checked' : '' }}> No
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="symptoms_other_{{$i}}" id="symptoms_other_{{$i}}" value="{{ isset($data->{'symptoms_other_'.$i}) ?? '' }}">
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
                                    <b>Name:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->name : '' }}<br>
                                    <b>Telephone Number:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->phone : '' }}<br>
                                    <b>Instituton:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->getHealthpost($data->hp_code) : '' }}<br>
                                    <b>Email:</b> {{ isset($data->checkedBy) && $data->checkedBy ? $data->checkedBy->user->email : '' }}<br>
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

        var i;
        for (i = 0; i < 11; ++i) {
            $("#date_of_follow_up_" + i).nepaliDatePicker({
                language: 'english',
            });
        }
    </script>
@endsection