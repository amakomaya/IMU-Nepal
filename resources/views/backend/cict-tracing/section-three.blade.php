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
                        <strong>CICT Form (3 of 3)</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('cict-tracing.section-three.update', $data->case_id), ['name' => 'createCase']) !!}
                        {{ method_field('PUT') }}
                        <div class="panel-body">

                            <div class="part-one">
                                <h4>VI. Vaccination Status</h4><br>

                                <div class="form-group">
                                    <label class="control-label">Has the Case under Investigation received SARS-CoV-2 vaccine (COVID-19 vaccine)?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ $data && $data->sars_cov2_vaccinated == "0" ? 'checked' : '' }} value="0">No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ $data && $data->sars_cov2_vaccinated == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="sars_cov2_vaccinated" class="sars_cov2_vaccinated"
                                                {{ $data && $data->sars_cov2_vaccinated == "2" ? 'checked' : '' }} value="2">Unknown
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
                                                        <select name="dose_one_name" class="form-control" id="dose_one_name">
                                                            <option {{ isset($data) && $data->dose_one_name == '' ? "selected" : "" }} value="">Select Name of Vaccine</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '1' ? "selected" : "" }} value="1">Verocell (Sinopharm)</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '2' ? "selected" : "" }} value="2">Covishield (The Serum Institute of India)</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '3' ? "selected" : "" }} value="3">Pfizer</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '4' ? "selected" : "" }} value="4">Moderna</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '5' ? "selected" : "" }} value="5">AstraZeneca</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '6' ? "selected" : "" }} value="6">Johnson & Johnson</option>
                                                            <option {{ isset($data) && $data->dose_one_name == '0' ? "selected" : "" }} value="10">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_one_date" id="dose_one_date" value="{{ $data->dose_one_date }}">
                                                    </td>
                                                </tr>
                                                <tr class="table-sars-cov-tr">
                                                    <td>
                                                        Dose 2
                                                    </td>
                                                    <td>
                                                        <select name="dose_two_name" class="form-control" id="dose_two_name">
                                                            <option {{ isset($data) && $data->dose_two_name == '' ? "selected" : "" }} value="">Select Name of Vaccine</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '1' ? "selected" : "" }} value="1">Verocell (Sinopharm)</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '2' ? "selected" : "" }} value="2">Covishield (The Serum Institute of India)</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '3' ? "selected" : "" }} value="3">Pfizer</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '4' ? "selected" : "" }} value="4">Moderna</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '5' ? "selected" : "" }} value="5">AstraZeneca</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '6' ? "selected" : "" }} value="6">Johnson & Johnson</option>
                                                            <option {{ isset($data) && $data->dose_two_name == '0' ? "selected" : "" }} value="10">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="dose_two_date" id="dose_two_date" value="{{ $data->dose_two_date }}">
                                                    </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <h4>VII. Information on Close Contact(s) of Case under Investigation</h4><br>

                                <div class="form-group">
                                    <label>Identify and list the following categories of persons who were exposed upto 2 days before and 10 days after the development of the symptoms OR 10 days before and 10 days after the date of sample collection in case of asymptomatic</label><br>
                                    
                                    <div class="form-group">
                                        <label for="reference_period_for">Reference Period From</label>
                                        <input type="text" class="form-control" value="{{ $data->close_ref_period_from_np }}" name="close_ref_period_from_np" id="close_ref_period_from_np"
                                                aria-describedby="help" placeholder="Enter Reference Period From">
                                        @if ($errors->has('close_ref_period_from_np'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('close_ref_period_from_np') }}</small>
                                        @endif
        
                                        <label for="close_ref_period_to">Reference Period To</label>
                                        <input type="text" class="form-control" value="{{ $data->close_ref_period_to_np }}" name="close_ref_period_to_np" id="close_ref_period_to_np"
                                                aria-describedby="help" placeholder="Enter Reference Period To">
                                        @if ($errors->has('close_ref_period_to_np'))
                                            <small id="help" class="form-text text-danger">{{ $errors->first('close_ref_period_to_np') }}</small>
                                        @endif
                                    </div>
                                    <input type="hidden" id="close_ref_period_from_np_bak" value="{{ $data->close_ref_period_from_np }}">
                                    <input type="hidden" id="close_ref_period_to_np_bak" value="{{ $data->close_ref_period_to_np }}">
                                </div>

                                <div class="form-group">
                                    <label>Total household members</label><br>
                                    <input type="text" class="form-control" value="{{ $data->household_count }}" name="household_count"
                                            aria-describedby="help" placeholder="Enter Total household members">
                                    @if ($errors->has('household_count'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('household_count') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Household Contacts during the reference period</label><br>
                                </div>
                                
                                <div class="form-group">
                                    <button type="button" class="btn btn-success btn-sm btn-add-close-contact-info" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Relationship</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-close-contact-info-tbody text-center">
                                                @if($data->household_details && $data->household_details != null && $data->household_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->household_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-close-contact-info-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-close-contact-info mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control " name="household_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="household_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="household_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="household_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="household_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1" {{ $sub_data->relationship == '1' ? 'selected' : '' }}>Family</option>
                                                            <option value="2" {{ $sub_data->relationship == '2' ? 'selected' : '' }}>Friend</option>
                                                            <option value="3" {{ $sub_data->relationship == '3' ? 'selected' : '' }}>Neighbour</option>
                                                            <option value="4" {{ $sub_data->relationship == '4' ? 'selected' : '' }}>Co-Worker</option>
                                                            <option value="0" {{ $sub_data->relationship == '0' ? 'selected' : '' }}>Others</option>
                                                        </select>
                                                        <input type="hidden" name="household_details_relationship_others[]" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="household_details_phone[]" value="{{ $sub_data->phone }}">
                                                        <input type="hidden" name="household_details_case_id[]" value="{{ $sub_data->case_id }}" class="household_details_case_id">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-close-contact-info-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-close-contact-info mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="household_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="household_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="household_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="household_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="household_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1">Family</option>
                                                            <option value="2">Friend</option>
                                                            <option value="3">Neighbour</option>
                                                            <option value="4">Co-Worker</option>
                                                            <option value="0">Others</option>
                                                        </select>
                                                        <input type="hidden" name="household_details_relationship_others[]" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="household_details_phone[]">
                                                        <input type="hidden" name="household_details_case_id[]" class="household_details_case_id" value="">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Did the case under investigation travelled in public/ private vehicle in the reference period?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="travel_vehicle" class="travel_vehicle"
                                                {{ $data->travel_vehicle == "0" ? 'checked' : '' }} value="0">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="travel_vehicle" class="travel_vehicle"
                                                {{ $data->travel_vehicle == "1" ? 'checked' : '' }} value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="travel_vehicle" class="travel_vehicle"
                                                {{ $data->travel_vehicle == "2" ? 'checked' : '' }} value="2">Unknown
                                    </label>
                                </div>

                                <div class="form-group travel_vehicle_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-travel-public" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Relationship</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-travel-public-tbody text-center">

                                                @if($data->travel_vehicle_details && $data->travel_vehicle_details != null && $data->travel_vehicle_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->travel_vehicle_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-travel-public-info-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-travel-public-info mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1" {{ $sub_data->relationship == '1' ? 'selected' : '' }}>Family</option>
                                                            <option value="2" {{ $sub_data->relationship == '2' ? 'selected' : '' }}>Friend</option>
                                                            <option value="3" {{ $sub_data->relationship == '3' ? 'selected' : '' }}>Neighbour</option>
                                                            <option value="4" {{ $sub_data->relationship == '4' ? 'selected' : '' }}>Co-Worker</option>
                                                            <option value="0" {{ $sub_data->relationship == '0' ? 'selected' : '' }}>Others</option>
                                                        </select>
                                                        <input type="hidden" name="travel_vehicle_details_relationship_others[]" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_phone[]" value="{{ $sub_data->phone }}">
                                                        <input type="hidden" name="travel_vehicle_details_case_id[]" value="{{ $sub_data->case_id }}" class="travel_vehicle_details_case_id">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-travel-public-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-travel-public mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="travel_vehicle_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1">Family</option>
                                                            <option value="2">Friend</option>
                                                            <option value="3">Neighbour</option>
                                                            <option value="4">Co-Worker</option>
                                                            <option value="0">Others</option>
                                                        </select>
                                                        <input type="hidden" name="travel_vehicle_details_relationship_others[]" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="travel_vehicle_details_phone[]">
                                                        <input type="hidden" name="travel_vehicle_details_case_id[]" class="travel_vehicle_details_case_id" value="">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Did the case under investigation provide direct care to anyone other than household contacts above in the reference period?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_direct_care" class="other_direct_care"
                                                {{ $data->other_direct_care == "0" ? 'checked' : '' }} value="0">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_direct_care" class="other_direct_care"
                                                {{ $data->other_direct_care == "1" ? 'checked' : '' }} value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_direct_care" class="other_direct_care"
                                                {{ $data->other_direct_care == "2" ? 'checked' : '' }} value="2">Unknown
                                    </label>
                                </div>

                                <div class="form-group other_direct_care_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-direct-care-any" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Age Unit</th>
                                                    <th>Sex</th>
                                                    <th>Relationship</th>
                                                    <th>Phone no.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-direct-care-any-tbody text-center">
                                                @if($data->other_direct_care_details && $data->other_direct_care_details != null && $data->other_direct_care_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->other_direct_care_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-direct-care-any-info-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-direct-care-any-info mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_age[]" value="{{ $sub_data->age }}">
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0" {{ $sub_data->age_unit == '0' ? 'selected' : '' }}>Years</option>
                                                            <option value="1" {{ $sub_data->age_unit == '1' ? 'selected' : '' }}>Months</option>
                                                            <option value="2" {{ $sub_data->age_unit == '2' ? 'selected' : '' }}>Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1" {{ $sub_data->sex == '1' ? 'selected' : '' }}>Male</option>
                                                            <option value="2" {{ $sub_data->sex == '2' ? 'selected' : '' }}>Female</option>
                                                            <option value="3" {{ $sub_data->sex == '3' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1" {{ $sub_data->relationship == '1' ? 'selected' : '' }}>Family</option>
                                                            <option value="2" {{ $sub_data->relationship == '2' ? 'selected' : '' }}>Friend</option>
                                                            <option value="3" {{ $sub_data->relationship == '3' ? 'selected' : '' }}>Neighbour</option>
                                                            <option value="4" {{ $sub_data->relationship == '4' ? 'selected' : '' }}>Co-Worker</option>
                                                            <option value="0" {{ $sub_data->relationship == '0' ? 'selected' : '' }}>Others</option>
                                                        </select>
                                                        <input type="hidden" name="other_direct_care_details_relationship_others[]" value="">
                                                        
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_phone[]" value="{{ $sub_data->phone }}">
                                                        <input type="hidden" name="other_direct_care_details_case_id[]" value="{{ $sub_data->case_id }}" class="other_direct_care_details_case_id">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-direct-care-any-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-direct-care-any mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_age[]">
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_age_unit[]" class="form-control">
                                                            <option value="">Select Age Unit</option>
                                                            <option value="0">Years</option>
                                                            <option value="1">Months</option>
                                                            <option value="2">Days</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_sex[]" class="form-control">
                                                            <option value="" selected>Select Gender</option>
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="other_direct_care_details_relationship[]" class="form-control">
                                                            <option value="" selected>Select Relationship</option>
                                                            <option value="1">Family</option>
                                                            <option value="2">Friend</option>
                                                            <option value="3">Neighbour</option>
                                                            <option value="4">Co-Worker</option>
                                                            <option value="0">Others</option>
                                                        </select>
                                                        <input type="hidden" name="other_direct_care_details_relationship_others[]" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_direct_care_details_phone[]">
                                                        <input type="hidden" name="other_direct_care_details_case_id[]" class="other_direct_care_details_case_id" value="">
                                                    </td>
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Did the case travel or attend school/workplace/hospitals/health care institutions/social gathering(s) during the reference period</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_attend_social" class="other_attend_social"
                                                {{ $data->other_attend_social == "0" ? 'checked' : '' }} value="0">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_attend_social" class="other_attend_social"
                                                {{ $data->other_attend_social == "1" ? 'checked' : '' }} value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="other_attend_social" class="other_attend_social"
                                                {{ $data->other_attend_social == "2" ? 'checked' : '' }} value="2">Unknown
                                    </label>
                                </div>

                                <div class="form-group other_attend_social_yes_class">
                                    <button type="button" class="btn btn-success btn-sm btn-add-school-reference" style="float: right"><i class="fa fa-plus"></i></button>
                                    <div class="clearfix"></div>
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th></th>
                                                    <th>Name of School/ Workplace/Social gathering Venue & Address OR Co-travellers</th>
                                                    <th>Number of Close Contacts & Details</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-school-reference-tbody text-center">
                                                @if($data->other_attend_social_details && $data->other_attend_social_details != null && $data->other_attend_social_details != '[]')
                                                <?php 
                                                    $sub_data_array = json_decode($data->other_attend_social_details);
                                                ?>
                                                @foreach($sub_data_array as $sub_data)
                                                <tr class="table-school-reference-tr">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-school-reference mt-1"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_attend_social_details_name[]" value="{{ $sub_data->name }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_attend_social_details_details[]" value="{{ $sub_data->details }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr class="table-school-reference-tr" data-row-id="0" style="display: none;">
                                                    <td width="95px">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-school-reference mt-1" style="display: none;"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_attend_social_details_name[]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="other_attend_social_details_details[]">
                                                    </td>
                                                </tr>
                                                @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php /*
                            <hr>
                            <div class="part-two">
                                <h4>Labotatory Information</h4><br>

                                <div class="form-group">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead style="background: #fff;">
                                                <tr>
                                                    <th rowspan="2">Samples collected</th>
                                                    <th rowspan="2">Date of Sample Collection</th>
                                                    <th rowspan="2"> RDT Ag Test</th>
                                                    <th rowspan="2">Date Sample Sent to lab for RT-PCR test</th>
                                                    <th colspan="2">If RT-PCR result is already known</th>
                                                </tr>
                                                <tr>
                                                    <th>Result Date</th>
                                                    <th>Result:Pos/Neg: </th>
                                                </tr>
                                            </thead>
                                            <?php
                                                if($data){
                                                    $sample_type = $data->sample_type ? json_decode($data->sample_type) : [];    
                                                }else {
                                                    $sample_type = [];
                                                }
                                            ?>
                                            <tbody class="table-travel-public-tbody text-center">
                                                <tr>
                                                    <td rowspan="2">
                                                        <input type="checkbox" name="sample_type[]" value="1" @if(in_array(1, $sample_type)) checked @endif>
                                                        Nasopharyngealswab<br>
                                                        <input type="checkbox" name="sample_type[]" value="2" @if(in_array(2, $sample_type)) checked @endif>
                                                        Oropharyngealswab<br>
                                                        <input type="checkbox" name="sample_type[]" value="3" @if(in_array(3, $sample_type)) checked @endif>
                                                        Broncheo-Alveolar Lavage 
                                                    </td>
                                                    <td rowspan="2">
                                                        <input type="text" class="form-control" name="collection_date_np" id="collection_date_np">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="ag_result_date_np" id="ag_result_date_np">
                                                    </td>
                                                    <td rowspan="2">
                                                        <input type="text" class="form-control" name="received_date_np" id="received_date_np">
                                                    </td>
                                                    <td rowspan="2">
                                                        <input type="text" class="form-control" name="pcr_result_date_np" id="pcr_result_date_np">
                                                    </td>
                                                    <td rowspan="2">
                                                        <select name="pcr_result" class="form-control">
                                                            <option value="" class="form-control" selected>Select Result</option>
                                                            <option value="3" class="form-control">Positive</option>
                                                            <option value="4" class="form-control">Negative</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="ag_result" class="form-control">
                                                            <option value="" class="form-control" selected>Select Result</option>
                                                            <option value="3" class="form-control">Positive</option>
                                                            <option value="4" class="form-control">Negative</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">Laboratory to which Sample was sent to for RT-PCR:</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                           */ ?>
                            
                            <hr>
                            <div class="part-three">
                                <div class="form-group">
                                    <h4>Data collector information</h4><br>
                                    <label>Form completion date</label><br>
                                    <input type="text" class="form-control" value="{{ $data ? $data->completion_date : '' }}" name="completion_date" id="completion_date"
                                            aria-describedby="help" placeholder="Enter Form Completion Date">
                                    @if ($errors->has('completion_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('completion_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" id="onset_date_bak" value="{{ $data->date_of_onset_of_first_symptom }}">

                            @if(isset($data->suspectedCase))
                                @if(isset($data->suspectedCase->latestAnc))
                                    <input type="hidden" id="sample_collection_date" value="{{ $data->data->suspectedCase->latestAnc->collection_date_en ?? date('Y-m-d') }}">
                                @else

                                <input type="hidden" id="sample_collection_date" value="{{ date('Y-m-d') }}">
                                @endif
                            @endif

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

    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#dose_one_date').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });
    $('#dose_two_date').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });
    $('#close_ref_period_from_np').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });
    $('#close_ref_period_to_np').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });
    $('#completion_date').nepaliDatePicker({
        language: 'english',
        disableAfter: currentDate
    });

    if($('#onset_date_bak').val() == ''){
        console.log('1');
        if($('#close_ref_period_from_np_bak').val() == ''){
            $('#close_ref_period_from_np').val(getPastDate($('#sample_collection_date').val(), 10));
        }
        if($('#close_ref_period_to_np_bak').val() == ''){
            $('#close_ref_period_to_np').val(getFutureDate($('#sample_collection_date').val(), 10));
        }
    }
    else{

        console.log('12');

        if($('#close_ref_period_from_np_bak').val() == ''){
            $('#close_ref_period_from_np').val(getOnsetPastDate($('#onset_date_bak').val(), 2));
        }
        if($('#close_ref_period_to_np_bak').val() == ''){
            $('#close_ref_period_to_np').val(getOnsetFutureDate($('#onset_date_bak').val(), 10));
        }
    }
    
    function getOnsetFutureDate(sel_date, sel_days){
        var np_date_obj = NepaliFunctions.ConvertToDateObject(sel_date, "YYYY-MM-DD");
        var en_date_obj = NepaliFunctions.BS2AD(np_date_obj);
        var en_date = NepaliFunctions.ConvertDateFormat(en_date_obj, "YYYY-MM-DD");
        var en_date = new Date(en_date);
        en_date.setDate(en_date.getDate() + sel_days);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }
    
    function getOnsetPastDate(sel_date, sel_days){
        var np_date_obj = NepaliFunctions.ConvertToDateObject(sel_date, "YYYY-MM-DD");
        var en_date_obj = NepaliFunctions.BS2AD(np_date_obj);
        var en_date = NepaliFunctions.ConvertDateFormat(en_date_obj, "YYYY-MM-DD");
        var en_date = new Date(en_date);
        en_date.setDate(en_date.getDate() - sel_days);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }
    
    function getFutureDate(sel_date, sel_days){
        var en_date = new Date(sel_date);
        en_date.setDate(en_date.getDate() + sel_days);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
    }
    
    function getPastDate(sel_date, sel_days){
        var en_date = new Date(sel_date);
        en_date.setDate(en_date.getDate() - sel_days);
        var en_11_date = en_date.getFullYear() + "-" + (en_date.getMonth() +1) + "-" + en_date.getDate();
        var en_11_date_obj = NepaliFunctions.ConvertToDateObject(en_11_date, "YYYY-MM-DD");
        en_11_date_obj = NepaliFunctions.AD2BS(en_11_date_obj);
        var en_11_date_final = NepaliFunctions.ConvertDateFormat(en_11_date_obj, "YYYY-MM-DD");
        return en_11_date_final;
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

    travel_vehicle();
    $('.travel_vehicle').on('change', function() {
        travel_vehicle();
    });
    function travel_vehicle(){
        if($('.travel_vehicle:checked').val() == '1'){
            $('.travel_vehicle_yes_class').show();
        }else {
            $('.travel_vehicle_yes_class').hide();
        }
    }

    other_direct_care();
    $('.other_direct_care').on('change', function() {
        other_direct_care();
    });
    function other_direct_care(){
        if($('.other_direct_care:checked').val() == '1'){
            $('.other_direct_care_yes_class').show();
        }else {
            $('.other_direct_care_yes_class').hide();
        }
    }

    other_attend_social();
    $('.other_attend_social').on('change', function() {
        other_attend_social();
    });
    function other_attend_social(){
        if($('.other_attend_social:checked').val() == '1'){
            $('.other_attend_social_yes_class').show();
        }else {
            $('.other_attend_social_yes_class').hide();
        }
    }
    // $('#collection_date_np').nepaliDatePicker({
    //     language: 'english',
    //     disableAfter: currentDate
    // });
    // $('#ag_result_date_np').nepaliDatePicker({
    //     language: 'english',
    //     disableAfter: currentDate
    // });
    // $('#received_date_np').nepaliDatePicker({
    //     language: 'english',
    //     disableAfter: currentDate
    // });
    // $('#pcr_result_date_np').nepaliDatePicker({
    //     language: 'english',
    //     disableAfter: currentDate
    // });

    // OrganizationMember::where('token', auth()->user()->token)->first()->id . '-' . strtoupper(bin2hex(random_bytes(3)));
    var org_id = {!! json_encode($org_id, JSON_HEX_TAG) !!}

    function randomString(){
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var charactersLength = characters.length;
        for(var i=0; i<7; i++){
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        result = org_id + '-' + result;
        return result;
    }

	$('.btn-add-close-contact-info').on('click', function() {
		var tr = $(".table-close-contact-info-tr").last();
		var count_row = tr.data("row-id");
		count_row++;

		var new_row = tr.clone()
		.find("input, select").val("").end()
		.show()
		.appendTo(".table-close-contact-info-tbody");

        new_row.find(".household_details_case_id").val(randomString());
		new_row.attr('data-row-id', count_row);
		new_row.find(".btn-remove-close-contact-info").show();
	});

    $('body').on('click', '.btn-remove-close-contact-info', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-close-contact-info-tr").remove();
    });

    $('.btn-add-travel-public').on('click', function() {
        var tr = $(".table-travel-public-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-travel-public-tbody");

        new_row.find(".other_direct_care_details_case_id").val(randomString());
        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-travel-public").show();
    });

    $('body').on('click', '.btn-remove-travel-public', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-travel-public-tr").remove();
    });

    $('.btn-add-direct-care-any').on('click', function() {
        var tr = $(".table-direct-care-any-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-direct-care-any-tbody");

        new_row.find(".travel_vehicle_details_case_id").val(randomString());
        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-direct-care-any").show();
    });

    $('body').on('click', '.btn-remove-direct-care-any', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-direct-care-any-tr").remove();
    });

    $('.btn-add-school-reference').on('click', function() {
        var tr = $(".table-school-reference-tr").last();
        var count_row = tr.data("row-id");
        count_row++;

        var new_row = tr.clone()
        .find("input, select").val("").end()
        .show()
        .appendTo(".table-school-reference-tbody");

        new_row.attr('data-row-id', count_row);
        new_row.find(".btn-remove-school-reference").show();
    });

    $('body').on('click', '.btn-remove-school-reference', function() {
        if(!confirm('Are you sure?')){
            return;
        }
        var $this = $(this);
        $this.parents(".table-school-reference-tr").remove();
    });

    </script>
@endsection