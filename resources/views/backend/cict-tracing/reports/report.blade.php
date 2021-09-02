@extends('layouts.backend.app')
@section('content')
<style>
    input[type="checkbox"][readonly] {
    pointer-events: none;
    }
</style>

<div id="page-wrapper">
    <header>
        <img src="{{ asset('images/v-card/gov_logo.png') }}" width="100" height="75" alt="" />
        <div class="header-title">   
            <p>Government of Nepal</p>
            <p>Ministry of Health and Population</p>
            <p>Department of Health Services</p>
            <p>Epidemiology and Disease Control Division</p>
        </div>
        <div class="side-header">Unique ID (in IMU app): {{ $data ? $data->case_id : '' }}<u></u></div>
    </header>
    <div class="investigation-form">
        <h3>A Form: Investigation Form for Probable or Confirmed Case of COVID-19</h3>
        <form action="#">
            <div style="padding-left: 0.5em;">
                <input style="padding-left: 0.5em;" type="checkbox" id="probable-case" name="probable-case" {{ isset($data) && $data->case_what == '0' ? 'checked' : '' }}/>
                <label for="probable-case"> Probable case</label>
            </div>
            <div style="padding-left: 0.5em;">
                <input style="padding-left: 0.5em;" type="checkbox" id="confirmed-case*" name="confirmed-case*" {{ isset($data) && $data->case_what == '1' ? 'checked' : '' }}/>
                <label for="confirmed-case*"> Confirmed case*</label>
            </div>

            <p style="padding-left: 0.5em;">(*Please see Page 4 for Case Definitions)</p>
        </form>
        <div class="info">
            <p>Date of case received by health authority: <u>{{ $data ? $data->case_received_date : '' }}</u></p>
            <p>Date of CICT initiated: <u>{{ $data ? $data->cict_initiated_date : '' }}</u></p>
            <p>Name and Address of the reporting Institution: <u>{{ $data && $data->checkedBy ? $data->checkedBy->getHealthpost($data->hp_code) : '' }}</u></p>
        </div>

        <section class="section-1">
            <h5>Section 1: Personal Information</h5>

            <div class="info-personal">
                <div class="pi-1">
                    <p>Unique Identifier (Case Epi Id): <u>{{ $data ? $data->case_id : '' }}</u></p>
                    <p>Father/mother’s name: <u></u></p>
                    <div class="date">
                        <p style="padding-right: 2em !important;">Age: <u>{{ $data ? $data->age : '' }}</u></p>
                        <p style="padding-right: 2em !important;">
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" name="age_unit" value="" @if($data && $data->age_unit == 0) checked readonly @else disabled @endif>
                                <label for="Years"> Years</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" name="age_unit" value="" @if($data && $data->age_unit == 1) checked readonly @else disabled @endif>
                                <label for="Months"> Months</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" name="age_unit" value="" @if($data && $data->age_unit == 2) checked readonly @else disabled @endif>
                                <label for="Unknown"> Days</label>
                            </div>
                        </p>
                    </div>
                    <p>Contact number: <u>{{ $data ? $data->emergency_contact_one : '' }}</u></p>
                </div>
                <div class="pi-2">
                    <p>Name: <u>{{ $data ? $data->name : ''}}</u></p>
                    <div style="display: flex; flex-direction: row;">
                            <p>Sex:</p>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Male" name="Male" value="" @if($data && $data->sex == 1) checked readonly @else disabled @endif>
                                <label for="Male"> Male</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Female" name="Female" value="" @if($data && $data->sex == 2  ) checked readonly @else disabled @endif>
                                <label for="Female"> Female</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Unknown" name="Unknown" value="" @if($data && $data->sex == 3) checked readonly @else disabled @endif>
                                <label for="Unknown"> Unknown</label>
                            </div> 
                        </div>
                    <?php
                        $nationality = '';
                        if($data){
                            if($data->nationality == '167'){
                                $nationality = 'Nepal';
                            }elseif($data->nationality == '104'){
                                $nationality = 'India';
                            }elseif($data->nationality == '47'){
                                $nationality = 'China';
                            }elseif($data->nationality == '300'){
                                $nationality = 'Other';
                            }
                        }
                    ?>
                    <p>Nationality: <u>{{ $nationality }}</u></p>
                    <p>Alternate contact number: <u>{{ $data ? $data->emergency_contact_two : '' }}</u></p>
                </div>
            </div>

            <sub-section>
                <h5>Current Address</h5>
                <table>
                    <tr>
                        <td>Province: <u>{{ $data ? $data->province->province_name : '' }}</u></td>
                        <td colspan="2">District: <u>{{ $data ? $data->municipality->district_name : '' }}</u></td>
                    </tr>
                    <tr>
                        <td>
                            Municipality: <u>{{ $data ? $data->municipality->municipality_name : '' }}</u> <br />
                            <p>If information is given by any other than case,</p>
                            <p>Name of the informant: <u>{{ $data ? $data->informant_name : '' }}</u></p>
                        </td>
                        <td>
                            <p>Ward No: {{ $data ? $data->ward : ''}}</p>
                            <?php
                                $relationship = '';
                                if($data){
                                    if($data->informant_relation == '1'){
                                        $relationship = 'Family';
                                    }elseif($data->informant_relation == '2'){
                                        $relationship = 'Friends';
                                    }elseif($data->informant_relation == '3'){
                                        $relationship = 'Neighbour';
                                    }elseif($data->informant_relation == '4'){
                                        $relationship = 'Relatives';
                                    }elseif($data->informant_relation == '5'){
                                        $relationship = 'Co-Worker';
                                    }if($data->informant_relation == '0'){
                                        $relationship = 'Other';
                                    }
                                }
                            ?>
                            <p>Relationship: <u>{{ $relationship }}</u></p>
                        </td>
                        <td>
                            <p>Tole/Landmark: {{ $data ? $data->tole : '' }}</p>
                            <p>Contact no: {{ $data ? $data->informant_phone : '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Case managed at:</b>
                            <div class="isolatedAt">
                                <b>Isolated at: </b>
                                <input type="checkbox" id="home" name="home" value="" @if($data && $data->case_managed_at == 1) checked readonly @else disabled @endif/>
                                <label for="home"> Home</label>
                                <input type="checkbox" id="institution" name="institution" value="" @if($data && $data->case_managed_at == 3) checked readonly @else disabled @endif />
                                <label for="institution"> Institution <u></u></label>
                            </div>
                            <div class="isolatedAt">
                                <b>Admitted at: </b>
                                <input type="checkbox" id="hospital" name="hospital" value="" @if($data && $data->case_managed_at == 4) checked readonly @else disabled @endif/>
                                <label for="hospital"> Hospital <u></u></label>
                            </div>
                        </td>
                        <td colspan="2">
                            <b>Details:</b>
                            <div class="detail-checkbox">
                                <input type="checkbox" id="ward" name="ward" value="" @if($data && $data->case_managed_at_hospital == 1) checked readonly @else disabled @endif />
                                <label for="ward"> In Ward</label>
                                <input type="checkbox" id="ICU" name="ICU" value="" @if($data && $data->case_managed_at_hospital == 2) checked readonly @else disabled @endif/>
                                <label for="ICU"> In ICU <u></u></label>
                                <input type="checkbox" id="ventilator" name="ventilator" value="" @if($data && $data->case_managed_at_hospital == 3) checked readonly @else disabled @endif/>
                                <label for="ventilator"> On Ventilator <u></u></label>
                            </div>
                            <b>Date of Admission: </b><u>{{ $data ? $data->case_managed_at_hospital_date : '' }}</u>
                        </td>
                    </tr>
                </table>
            </sub-section>
        </section>

        <section class="section-2">
            <h5>Section 2: Clinical and Epidemiological Information</h5>

            <p>I. Symptoms</p>
            <div class="symptoms">
                <p>
                    2.1. Currently symptomatic: 
                    <input type="checkbox" id="yes" name="yes" value=""  @if($data->symptoms_recent == 1) checked readonly @else disabled @endif/>
                    <label for="yes"> Yes</label>
                    <input type="checkbox" id="no" name="no" value="" @if($data->symptoms_recent == 0) checked readonly @else disabled @endif/>
                    <label for="no"> No</label>      
                </p>
                <p>
                    2.2 If no, whether symptomatic anytime during the past 2 weeks
                    <input type="checkbox" id="yes" name="yes" value="" @if($data->symptoms_two_weeks == 1) checked readonly @else disabled @endif/>
                    <label for="yes"> Yes</label>
                    <input type="checkbox" id="no" name="no" value="" @if($data->symptoms_two_weeks == 0) checked readonly @else disabled @endif/>
                    <label for="no"> No <u></u></label>
                </p>
                <p>
                    If answer to 2.1 or 2.2 is Yes, Date of Onset of First set of Symptoms  <u style="padding: 0 0.7em !important;">{{ $data ? $data->date_of_onset_of_first_symptom : '' }}</u> and check any and all applicable symptoms listed below:
                </p>
                <div class="list-symptoms">
                    <div class="row col-md-12">
                        <?php
                            if($data){
                                $symptoms = $data->symptoms ? json_decode($data->symptoms) : [];    
                            }else {
                                $symptoms = [];
                            }
                        ?>
                        <div class="symptoms-1 col-md-3">
                            <div>
                                <input type="checkbox" name="symptoms[]" value="1" @if(in_array(4, $symptoms)) checked @endif readonly>
                                <label for="Fever">Fever</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="2" @if(in_array(6, $symptoms)) checked @endif readonly>
                                <label for= "Cough">Cough</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="3" @if(in_array(5, $symptoms)) checked @endif readonly>
                                <label for= " General weakness / Tiredess"> General weakness / Tiredess</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="4" @if(in_array(19, $symptoms)) checked @endif readonly>
                                <label for= "Headache">Headache</label>
                            </div>
                        </div>
                        <div class="symptoms-3 col-md-3">
                            <div>
                                <input type="checkbox" name="symptoms[]" value="9" @if(in_array(13, $symptoms)) checked @endif readonly>
                                <label for= "Pain in the muscles">Pain in the muscles</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="10" @if(in_array(7, $symptoms)) checked @endif readonly>
                                <label for= "Sore Throat">Sore Throat</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="11" @if(in_array(8, $symptoms)) checked @endif readonly>
                                <label for= "Runny nose">Runny nose</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="12" @if(in_array(9, $symptoms)) checked @endif readonly>
                                <label for= "Shortness of breath">Shortness of breath</label>
                            </div>
                        </div>
                        <div class="symptoms-5 col-md-3">
                            <div>
                                <input type="checkbox" name="symptoms[]" value="17" @if(in_array(18, $symptoms)) checked @endif readonly>
                                <label for= "Nausea / Vomiting / Loss of appetite">Nausea / Vomiting / Loss of appetite</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="18" @if(in_array(17, $symptoms)) checked @endif readonly>
                                <label for= "Diarrhea">Diarrhea</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="19" @if(in_array(10, $symptoms)) checked @endif readonly>
                                <label for= "Irritability / Confusion">Irritability / Confusion</label>
                            </div>
                            <div>
                                <input type="checkbox" name="symptoms[]" value="20" @if(in_array(12, $symptoms)) checked @endif readonly>
                                <label for= "Recent loss of smell">Recent loss of smell</label>
                            </div>
                        </div>
                        <div class="symptoms-6 col-md-3">
                            <div>
                                <input type="checkbox" name="symptoms[]" value="21" @if(in_array(11, $symptoms)) checked @endif readonly>
                                <label for= "Recent loss of taste">Recent loss of taste</label>
                            </div>
                            <div>
                                <input type="checkbox" id="specify" name="specify" value="" @if(!is_null($data->symptoms_specific)) checked @endif readonly/>
                                <label for="specify"> Others, specify: <span style="font-weight: 500 !important;">{{ $data ? $data->symptoms_specific : '' }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p>II. Underlying medical conditions or disease / comorbidity (check all that apply):</p>
            <div class="comorbidity"> 
                <?php
                    if($data){
                        $symptomsComorbidity = $data->symptoms_comorbidity ? json_decode($data->symptoms_comorbidity) : [];    
                    }else {
                        $symptomsComorbidity = [];
                    }
                ?>
                <div class="comorbidity1 col-md-6">
                    <div>
                        <input type="checkbox" id="recent-loss-of-smell" name="recent-loss-of-smell" value="" @if(in_array(5, $symptomsComorbidity) || in_array(16, $symptomsComorbidity) || in_array(17, $symptomsComorbidity)) checked @endif readonly/>
                        @if(in_array(5, $symptomsComorbidity))
                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>One</u> )</label>
                        @elseif(in_array(16, $symptomsComorbidity))
                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Two</u> )</label>
                        @elseif(in_array(17, $symptomsComorbidity))
                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Three</u> )</label>
                        @else
                        <label for="recent-loss-of-smell">Pregnancy (trimester: <u></u> )</label>
                        @endif
                    </div>
                    <div>
                        <input type="checkbox" id="post-delivery" name="post-delivery" value="" @if(in_array(18, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="post-delivery">Post-delivery <span> (< 6 weeks) </span></label>
                    </div>
                    <div>
                        <input type="checkbox" id="cardiovascular-disease" name="cardiovascular-disease" value="" @if(in_array(7, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="cardiovascular-disease">Cardiovascular disease, including hypertension</label>
                    </div>
                    <div>
                        <input type="checkbox" id="diabetes" name="diabetes" value="" @if(in_array(1, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="diabetes"> Diabetes</label>
                    </div>
                </div>
                <div class="comorbidity2 col-md-6">
                    <div>
                        <input type="checkbox" id="malignancy" name="malignancy" value="" @if(in_array(14, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="malignancy"> Malignancy</label>
                    </div>
                    <div>
                        <input type="checkbox" id="COPD" name="COPD" value="" @if(in_array(19, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="COPD"> COPD</label>
                    </div>
                    <div>
                        <input type="checkbox" id="Chronic-Kidney-Diseases" name="Chronic-Kidney-Diseases" value="" @if(in_array(10, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="Chronic-Kidney-Diseases"> Chronic Kidney Diseases</label>
                    </div>
                    <div>
                        <input type="checkbox" id="malignancy" name="malignancy" value="" @if(!is_null($data->symptoms_comorbidity_specific)) checked @endif readonly/>
                        <label for="malignancy"> Others, specify: <span style="font-weight: 500 !important;">{{ $data ? $data->symptoms_comorbidity_specific : '' }}</span></label>
                    </div>
                </div>
            </div>

            <div class="high-exposures">
                <span>&nbsp; </span>
                <div class="high-exposure-title">
                    <p>III. High exposure category of Case under Investigation belongs to (tick any that apply):</p>
                </div>
                <div class="col-md-12">
                    <div>
                        <input type="checkbox" id="health-care" name="health-care" value="" @if($data && $data->high_exposure == 1) checked readonly @else disabled @endif/>
                        <label for="health-care"> Health Care Work (any type, level & facility, including cleaning staff)</label>
                    </div>
                    <div>
                        <input type="checkbox" id="community-health" name="community-health" value="" @if($data && $data->high_exposure == 2) checked readonly @else disabled @endif/>
                        <label for="community-health"> Community Health / Immunization Clinic Volunteer</label>
                    </div>
                    <div>
                        <input type="checkbox" id="sanitary" name="sanitary" value="" @if($data && $data->high_exposure == 3) checked readonly @else disabled @endif/>
                        <label for="sanitary"> Sanitary/Waste Collection/Management Worker/Transport Driver/Helper</label>
                    </div>
                    <div>
                        <input type="checkbox" id="patient-dead-body" name="shortbreath" value="" @if($data && $data->high_exposure == 4) checked readonly @else disabled @endif/>
                        <label for="shortbreath"> Patient & Dead body Transport Driver/Helper </label>
                    </div>
                    <div>
                        <input type="checkbox" id="management-work" name="management-work" value="" @if($data && $data->high_exposure == 5) checked readonly @else disabled @endif/>
                        <label for="management-work"> Dead body management work</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="old-age-home" name="old-age-home" value="" @if($data && $data->high_exposure == 6) checked readonly @else disabled @endif/>
                            <label for="old-age-home"> Old Age Home/Care work </label>
                        </div>
                        <div>
                            <input type="checkbox" id="border-crossing" name="border-crossing" value="" @if($data && $data->high_exposure == 7) checked readonly @else disabled @endif/>
                            <label for="border-crossing"> Border Crossing / Point of Entry Staff </label>
                        </div>
                        <div>
                            <input type="checkbox" id="journalist" name="journalist" value="" @if($data && $data->high_exposure == 12) checked readonly @else disabled @endif/>
                            <label for="journalist"> Journalist </label>
                        </div>
                        <div>
                            <input type="checkbox" id="prisoner" name="prisoner" value="" @if($data && $data->high_exposure == 15) checked readonly @else disabled @endif/>
                            <label for="prisoner"> Prisoner </label>
                        </div>
                        <div>
                            <input type="checkbox" id="elected-representative" name="elected-representative" value="" @if($data && $data->high_exposure == 18) checked readonly @else disabled @endif />
                            <label for="elected-representative"> Local body Elected Representative </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="security-staff" name="security-staff" value="" @if($data && $data->high_exposure == 8) checked readonly @else disabled @endif/>
                            <label for="security-staff"> Any Security Staff</label>
                        </div>
                        <div>
                            <input type="checkbox" id="hotel-restaurant" name="hotel-restaurant" value="" @if($data && $data->high_exposure == 9) checked readonly @else disabled @endif/>
                            <label for="hotel-restaurant"> Hotel/Restaurant/Bar work</label>
                        </div>
                        <div>
                            <input type="checkbox" id="migrant" name="migrant" value="" @if($data && $data->high_exposure == 13) checked readonly @else disabled @endif />
                            <label for="migrant"> Migrant </label>
                        </div>
                        <div>
                            <input type="checkbox" id="teacher" name="teacher" value="" @if($data && $data->high_exposure == 16) checked readonly @else disabled @endif/>
                            <label for="teacher"> Teacher </label>
                        </div>
                        <div>
                            <input type="checkbox" id="bank-govt-office" name="bank-govt-office" value="" @if($data && $data->high_exposure == 19) checked readonly @else disabled @endif/>
                            <label for="bank-govt-office"> Bank/Govt Office / Public Corporation staff </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="farm-work" name="farm-work" value="" @if($data && $data->high_exposure == 10) checked readonly @else disabled @endif/>
                            <label for="farm-work"> Farm work</label>
                        </div>
                        <div>
                            <input type="checkbox" id="shop-worker" name="shop-worker" value="" @if($data && $data->high_exposure == 11) checked readonly @else disabled @endif/>
                            <label for="shop-worker"> Shop/Store worker</label>
                        </div>
                        <div>
                            <input type="checkbox" id="refugee" name="refugee" value="" @if($data && $data->high_exposure == 14) checked readonly @else disabled @endif/>
                            <label for="refugee"> Refugee </label>
                        </div>
                        <div>
                            <input type="checkbox" id="Student" name="Student" value="" @if($data && $data->high_exposure == 17) checked readonly @else disabled @endif/>
                            <label for="Student"> Old Age Home/Care work </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div>
                            <input type="checkbox" id="bank-govt-office" name="bank-govt-office" value="" @if($data && $data->high_exposure == 20) checked readonly @else disabled @endif/>
                            <label for="bank-govt-office"> UN / Development Partner / INGO / NGO (Frontline worker)</label>
                        </div>
                        <div>
                            <input type="checkbox" id="specify-other" name="specify-other" value="" @if($data && $data->high_exposure == 0) checked readonly @else disabled @endif/>
                            <label for="specify-other"> Others (specify): {{ $data ? $data->high_exposure_other : '' }}</label>
                        </div>
                    </div>
                </div>
                <span>&nbsp;</span>
                <div class="travel">
                    <div class="travel-head">
                        <p>IV. Travel during 14 days before OR aftersymptom onset or date of sample collection for testing:</p>
                        <div class="travel-checkbox">
                            <div class="checkbox-tra">
                                <input type="checkbox" id="vehicle1" name="vehicle1" value="Yes" @if($data->travelled_14_days == 1) checked readonly @else disabled @endif>
                                <label for="vehicle1"> Yes </label>
                            </div>
                            <div class="checkbox-tra">
                                <input type="checkbox" id="vehicle1" name="vehicle1" value="No" @if($data->travelled_14_days == 0) checked readonly @else disabled @endif>
                                <label for="vehicle1"> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="travel-body">
                        <div>
                            <span>If yes fill in the table below both for foreign and domestic travel in the relevant columns of the table </span>
                            <table class="foreign-domestic-table">
                                <tr>
                                    <th style="background-color:#d8e3e7">Place of Departure from </th>
                                    <th style="background-color:#d8e3e7">Place of arrival to</th>
                                    <th style="background-color:#f0e3ca">Date of Departure from or to the place
                                    </th>
                                    <th style="background-color:#f0e3ca">Date of Arrival in Nepal or Current place of Residence
                                    </th>
                                    <th style="background-color:#f0e3ca">Mode of travel [ Air, Public Transport,Private Vehicle]</th>
                                    <th style="background-color:#f0e3ca">Flight/Vehicle No./ Bus Route / Driver Contact No.</th>
                                </tr>
                                @if($data->travelled_14_days_details && $data->travelled_14_days_details != null && $data->travelled_14_days_details != '[]')
                                <?php 
                                    $sub_data_array = json_decode($data->travelled_14_days_details);
                                ?>
                                @foreach($sub_data_array as $sub_data)
                                <tr>
                                    <td>{{ $sub_data->departure_from }}</td>
                                    <td>{{ $sub_data->arrival_to }}</td>
                                    <td>{{ $sub_data->departure_date }}</td>
                                    <td>{{ $sub_data->arrival_date }}</td>
                                    <?php
                                        $travel_mode = '';
                                        if( $sub_data->travel_mode == '1'){
                                            $travel_mode = 'Air';
                                        }elseif( $sub_data->travel_mode == '2'){
                                            $travel_mode = 'Public Transport';
                                        }elseif( $sub_data->travel_mode == '3'){
                                            $travel_mode = 'Private Vehicle';
                                        }elseif( $sub_data->travel_mode == '0'){
                                            $travel_mode = 'Other';
                                        }
                                    ?>
                                    <td>{{ $travel_mode }}</td>
                                    <td>{{ $sub_data->vehicle_no }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="information">
                <div class="information-head">
                    <p>V. Information on Source of Exposure of Case under Investigation</p>
                </div>
                <div class="box">
                    <div class="box-head">
                        <b>Identify the following categories of persons who the case might have contracted the infection from, upto 14 days before the development of the symptoms OR 24 days prior to the date of sample collection in case of asymptomatic Reference
                            period: From <u>{{ $data ? $data->exposure_ref_period_from_np : "" }}</u> To <u>{{ $data ? $data->exposure_ref_period_to_np : '' }}</u>
                        </b>
                    </div>
                    <div class="box-body">
                        <p>Did any known case(s) of COVID-19 live in the same household as the case under investigation during the reference period? </p>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                    <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->same_household == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if($data && $data->same_household == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->same_household == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>

                            {{-- <div class="col-md-4">
                                <p>Total household members: </p>
                            </div> --}}
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Phone no.</th>
                            </tr>
                            @if($data->same_household_details && $data->same_household_details != null && $data->same_household_details != '[]')
                            <?php 
                                $sub_data_array = json_decode($data->same_household_details);
                            ?>
                            @foreach($sub_data_array as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$key_sub }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                            
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case had close contact with probable and confirmed case/ person with travel history from COVID-19 affected place
                            during the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->close_contact == 1) checked readonly @else disabled @endif/>
                                    <label style="padding-left: 0.5em;" for="yes"> yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" @if($data && $data->close_contact == 0) checked readonly @else disabled @endif />
                                    <label style="padding-left: 0.5em;" for="no"> no</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->close_contact == 2) checked readonly @else disabled @endif />
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Phone no.</th>
                            </tr>
                            @if($data->close_contact_details && $data->close_contact_details != null && $data->close_contact_details != '[]')
                            <?php 
                                $sub_data_array = json_decode($data->close_contact_details);
                            ?>
                            @foreach($sub_data_array as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$key_sub }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case under investigation provide direct care to known case(s) of COVID-19 during the reference period?
                        </b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->direct_care == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if($data && $data->direct_care == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->direct_care == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Phone no.</th>
                            </tr>
                            @if($data->direct_care_details && $data->direct_care_details != null && $data->direct_care_details != '[]')
                            <?php 
                                $sub_data_array = json_decode($data->direct_care_details);
                            ?>
                            @foreach($sub_data_array as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$key_sub }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case under investigation attend School/Workplace/hospitals/healthcare institution/ Social gathering(s) during the
                            reference period?
                        </b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->attend_social == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if($data && $data->attend_social == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->attend_social == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name of School/ Workplace/Social
                                    gathering Venue & Address
                                </th>
                                <th>Number of Close Contacts &
                                    Details
                                </th>
                                <th>Remarks</th>
                            </tr>
                            @if($data->attend_social_details && $data->attend_social_details != null && $data->attend_social_details != '[]')
                                <?php 
                                    $sub_data_array = json_decode($data->attend_social_details);
                                ?>
                                @foreach($sub_data_array as $key_sub => $sub_data)
                                <tr>
                                    <td>{{ ++$key_sub }}</td>
                                    <td>{{ $sub_data->name }}</td>
                                    <td>{{ $sub_data->phone }}</td>
                                    <td>{{ $sub_data->remarks }}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="vaccination-status">
                <div class="vacc-status-title">
                    <p>VI. Vaccination Status</p>
                </div>
                <div class="vacc-status-body">
                    <table style="margin-top: 1em;">
                        <tr>
                            <th width="30%" colspan="2">Has the Case under Investigation
                                received SARS-CoV-2 vaccine (COVID-19
                                vaccine)?
                            </th>
                            <th width="20%">
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="yes" name="yes" value=""@if($data && $data->sars_cov2_vaccinated == 1) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="No" name="No" value="" @if($data && $data->sars_cov2_vaccinated == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="No"> No</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->sars_cov2_vaccinated == 2)  checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="unknown"> Unknown</label>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" rowspan="2">If <b>Yes,</b>Name of the Vaccine(Product/Brand name)</td>
                            <th rowspan="2">Date of Vaccination</th>
                            <td colspan="4">Source of Information (check multiple options if needed)</td>
                        </tr>
                        <tr>
                            <td>Vaccination Card</td>
                            <td>VaccinationRegister</td>
                            <td>Recall</td>
                            <td>Others</td>
                        </tr>
                        <tr>
                            <td width="5%">Dose 1</td>
                            <td>{{ $data->dose_one_name ? $data->vaccine->name : '' }}</td>
                            <td>{{ $data->dose_one_date }}</td>
                            <td style="display: flex; justify-content: center;"> 
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" />
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" />
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" />
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 2em;">
                                <input type="checkbox" id="no" name="no" value="" />
                                <label for="no">no</label>
                            </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" />
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 3em;">
                                <input type="checkbox" id="no" name="no" value="" />
                                <label for="no">no</label>
                            </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" />
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 0.2em;">
                                <input type="checkbox" id="no" name="no" value="" />
                                <label for="no">no</label>
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dose 2</td>
                            <td>{{ $data->dose_one_name ? $data->vaccine->name : '' }}</td>
                            <td>{{ $data->dose_two_date }}</td>
                            <td style="display: flex; border: none; justify-content: center; ">
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value=""/>
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" />
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" />
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" />
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" />
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" >
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" />
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 0.2em;">
                                <input type="checkbox" id="no" name="no" value="" />
                                <label for="no">no</label>
                            </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="information">
                <div class="information-head">
                    <p>VII. Information on Close Contact(s) of Case under Investigation</p>
                </div>
                <div class="box">
                    <div style="display: flex; flex-direction: column;" class="box-head">
                        <b>Identify and list the following categories of persons who were exposed upto 2 days before and 10 days of the development of
                            the symptoms OR 10 days before and 10 days after the date of sample collection in case of asymptomatic
                        </b><br>
                        <div>
                            <b>Reference period: From <u>{{ $data->close_ref_period_from_np }}</u> To <u>{{ $data->close_ref_period_to_np }}</u></b>
                        </div>
                    </div>
                    <hr style="margin: 0; padding: 0;">
                    <div class="box-body">
                        <div>
                            <div class="col-md-6">
                                <b>Household Contacts during the reference period:</b>
                            </div>

                            <div class="col-md-6">
                                <p>Total household members: {{ $data->household_count }}</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Relationship</th>
                                <th>Health / COVID Test Status</th>
                                <th>Contact Number</th>
                            </tr>
                            @if($data->closeContacts->count() && $data->closeContacts->where('contact_type', 1)->count())
                            @php $count = 0 @endphp
                            @foreach($data->closeContacts->where('contact_type', 1) as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$count }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                    $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <?php
                                    $relationship = '';
                                    if($data){
                                        if($data->informant_relation == '1'){
                                            $relationship = 'Family';
                                        }elseif($data->informant_relation == '2'){
                                            $relationship = 'Friends';
                                        }elseif($data->informant_relation == '3'){
                                            $relationship = 'Neighbour';
                                        }elseif($data->informant_relation == '4'){
                                            $relationship = 'Relatives';
                                        }elseif($data->informant_relation == '5'){
                                            $relationship = 'Co-Worker';
                                        }if($data->informant_relation == '0'){
                                            $relationship = 'Other';
                                        }
                                    }
                                ?>
                                <td>{{ $relationship }}</td>
                                <td></td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case under investigation travelled in public/ private vehicle in the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->travel_vehicle == 1) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" @if($data && $data->travel_vehicle == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->travel_vehicle == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Relationship</th>
                                <th>Health / COVID Test Status</th>
                                <th>Contact Number</th>
                            </tr>

                            @if($data->closeContacts->count() && $data->closeContacts->where('contact_type', 2)->count())
                            @php $count = 0 @endphp
                            @foreach($data->closeContacts->where('contact_type', 2) as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$count }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                    $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <?php
                                    $relationship = '';
                                    if($data){
                                        if($data->informant_relation == '1'){
                                            $relationship = 'Family';
                                        }elseif($data->informant_relation == '2'){
                                            $relationship = 'Friends';
                                        }elseif($data->informant_relation == '3'){
                                            $relationship = 'Neighbour';
                                        }elseif($data->informant_relation == '4'){
                                            $relationship = 'Relatives';
                                        }elseif($data->informant_relation == '5'){
                                            $relationship = 'Co-Worker';
                                        }if($data->informant_relation == '0'){
                                            $relationship = 'Other';
                                        }
                                    }
                                ?>
                                <td>{{ $relationship }}</td>
                                <td></td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case under investigation provide direct care to anyone other than household contacts above in the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->other_direct_care == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" @if($data && $data->other_direct_care == 1) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->other_direct_care == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Relationship</th>
                                <th>Health / COVID Test Status</th>
                                <th>Contact Number</th>
                            </tr>

                            @if($data->closeContacts->count() && $data->closeContacts->where('contact_type', 3)->count())
                            @php $count = 0 @endphp
                            @foreach($data->closeContacts->where('contact_type', 3) as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$count }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->age }}</td>
                                <?php
                                    $gender = '';
                                    if($sub_data->sex == 1){
                                        $gender = 'Male';
                                    }elseif($sub_data->sex == 2){
                                        $gender = 'Female';
                                    }elseif($sub_data->sex == 3){
                                        $gender = 'Other';
                                    }
                                ?>
                                <td>{{ $gender }}</td>
                                <?php
                                    $relationship = '';
                                    if($data){
                                        if($data->informant_relation == '1'){
                                            $relationship = 'Family';
                                        }elseif($data->informant_relation == '2'){
                                            $relationship = 'Friends';
                                        }elseif($data->informant_relation == '3'){
                                            $relationship = 'Neighbour';
                                        }elseif($data->informant_relation == '4'){
                                            $relationship = 'Relatives';
                                        }elseif($data->informant_relation == '5'){
                                            $relationship = 'Co-Worker';
                                        }if($data->informant_relation == '0'){
                                            $relationship = 'Other';
                                        }
                                    }
                                ?>
                                <td>{{ $relationship }}</td>
                                <td></td>
                                <td>{{ $sub_data->phone }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case travel or attend school/workplace/hospitals/health care institutions/social gathering(s) during the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if($data && $data->other_attend_social == 1) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" @if($data && $data->other_attend_social == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if($data && $data->other_attend_social == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name of School/ Workplace/Social gathering Venue &
                                    Address OR Co-travellers
                                </th>
                                <th>Number of Close Contacts & Details</th>
                            </tr>
                            @if($data->other_attend_social_details && $data->other_attend_social_details != null && $data->other_attend_social_details != '[]')
                            <?php 
                                $sub_data_array = json_decode($data->other_attend_social_details);
                            ?>
                            @foreach($sub_data_array as $key_sub => $sub_data)
                            <tr>
                                <td>{{ ++$key_sub }}</td>
                                <td>{{ $sub_data->name }}</td>
                                <td>{{ $sub_data->details }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-3">
            <h5>Section 3: Laboratory information</h5>
            <table>
                <tr>
                    <th rowspan="2" colspan="2">Samples collected</th>
                    <th rowspan="2">Date of Sample Collection (DD/MM/YYYY)</th>
                    <th rowspan="2"> RDT Ag Test (DD/MM/YYYY)</th>
                    <th rowspan="2">Date Sample Sent to lab for RT-PCR test (DD/MM/YYYY)</th>
                    <th colspan="2">If RT-PCR result is already known</th>
                </tr>
                <tr>
                    <th>Result Date (DD/MM/YYYY): </th>
                    <th>Result:Pos/Neg: </th>
                </tr>
                <tr>
                    <td rowspan="2">Nasopharyngealswab or Oropharyngealswab or Broncheo-Alveolar Lavage </td>
                    <td rowspan="2">
                    <span class="col-md-3">
                        <input type="checkbox" id="yes" name="yes" value="" >
                        <label for="yes">yes</label>
                    </span>
                        <span class="col-md-3" style="padding-left: 2em;">
                        <input type="checkbox" id="no" name="no" value="" >
                        <label for="no">no</label>
                    </span>
                    </td>
                    <td rowspan="2"></td>
                    <td> <b>Date:</b> </td>
                    <td rowspan="2"> </td>
                    <td rowspan="2"> </td>
                    <td rowspan="2"> 
                    </td>
                </tr>
                <tr>
                    <th>Result: Pos/Neg</th>
                </tr>
                <tr>
                    <td colspan="3">Laboratory to which Sample was sent to for RT-PCR:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </section>

        <section class="section-4">
            <h5>Section 4: Data collector information</h5>
            <table>
                <tr>
                <td>Name: {{ $data->checkedBy ? $data->checkedBy->name : '' }}</td>
                <td>Telephone: {{ $data->checkedBy ? $data->checkedBy->phone : '' }}</td>
                </tr>
                <tr>
                    <td>Institution: {{ $data->checkedBy ? $data->checkedBy->getHealthpost($data->hp_code) : '' }}</td>
                    <td>Email: {{ $data->checkedBy ? $data->checkedBy->user->email : '' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Form completion date: <u>{{ $data ? $data->completion_date : '' }}</u></td>
                </tr>
            </table>
        </section>

        @if($data->contact && $data->contact->count())
        @php $follow_count = 0; @endphp
        @foreach($data->contact as $key => $contact)
        <div class="form-b1">
            <h4>Form B1 - Contact Interview Form {{ ++$key }}</h4>
            <table>
                <tr>
                    <th style="background-color:#8eaadb" colspan="2">1. Case Information</th>
                </tr>
                <tr>
                    <td class="col-md-6">
                        <b>Name of the Case: <u>{{ $data->name }}</u></b>
                    </td>
                    <td class="col-md-6">
                        <b>EPID ID: <u>{{ $data->case_id }}</u></b>
                    </td>
                </tr>
                <tr>
                    <th style="background-color:#8eaadb" colspan="2">2. Personal details of the contact</th>
                </tr>
                <tr>
                    <td>EPID ID no: {{ $contact->case_id }}</td>
                    <td>Name: {{ $contact->name }}</td>
                </tr>
                <tr>
                    <td>Date of birth (dd/mm/yyyy)/Age <u>{{ $contact->age }}</u></td>
                    <td>
                        <div style="display: flex; flex-direction: row;">
                            <p>Sex:</p>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Male" name="Male" value="" @if($contact->sex == 1) checked readonly @else disabled @endif>
                                <label for="Male"> Male</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Female" name="Female" value="" @if($contact->sex == 2) checked readonly @else disabled @endif>
                                <label for="Female"> Female</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Unknown" name="Unknown" value="" @if($contact->sex == 3) checked readonly @else disabled @endif>
                                <label for="Unknown"> Unknown</label>
                            </div> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-md-6">
                        <?php
                        $nationality = '';
                        if($contact->nationality == '167'){
                            $nationality = 'Nepal';
                        }elseif($contact->nationality == '104'){
                            $nationality = 'India';
                        }elseif($contact->nationality == '47'){
                            $nationality = 'China';
                        }elseif($contact->nationality == '300'){
                            $nationality = 'Other';
                        }
                    ?>
                        <b>Nationality <u>{{ $nationality }}</u></b>
                    </td>
                    <td class="col-md-6">
                        <?php
                            if($contact->relationship == 1){
                                $relation = 'Family';
                            }elseif($contact->relationship == 2){
                                $relation = 'Friend';
                            }elseif($contact->relationship == 3){
                                $relation = 'Neighbour';
                            }elseif($contact->relationship == 4){
                                $relation = 'Relative';
                            }else{
                                $relation = '';
                            }
                        ?>
                        <b>Relation to the case: <u> {{ $relation }}</u></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="col-md-6">
                            <p>Current Address: </p>
                            <p>Province: {{ $contact->province->province_name }}</p>
                            <p>Municipality: {{ $contact->municipality->municipality_name}}</p>
                            <p>Tole/Landmark:{{ $contact->tole }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>District: {{ $contact->district->district_name }}</p>
                            <p>Ward: {{ $contact->ward }}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Current Location Name (fill only if the contact is temporarily staying in a quarantine facility, hotel or
                        similar place)
                    </td>
                </tr>
                <tr>
                    <td>Telephone (mobile) number: {{ $contact->emergency_contact_one }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Alternative Contact number: {{ $contact->emergency_contact_two }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Interview respondent information (if the persons providing the information is not the contact)</b>
                    </td>
                </tr>
                <tr>
                    <td>Name: {{ $contact->informant_name }}</td>
                    <?php
                        if($contact->informant_relation == 1){
                            $relation = 'Family';
                        }elseif($contact->informant_relation == 2){
                            $relation = 'Friend';
                        }elseif($contact->informant_relation == 3){
                            $relation = 'Neighbour';
                        }elseif($contact->informant_relation == 4){
                            $relation = 'Relative';
                        }else{
                            $relation = $contact->informant_relation_other;
                        }
                    ?>
                    <td>Relationship to the contact: {{ $relation }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>Mobile no. {{ $contact->informant_phone }}</td>
                </tr>
            </table>

            <table style="margin-top: 1em;">
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">3. Contacts clinical Information</th>
                </tr>
                <tr>
                    <td width="50%">
                        <div>
                            <p>3.1. Currently symptomatic: </p>
                            <div style="display: flex; flex-direction: row;">
                            
                                
                                <div style="padding-left: 1em;">
                                    <input type="checkbox" id="yes" name="yes" value=""  @if($contact->symptoms_recent == 1) checked readonly @else disabled @endif/>
                                    <label for="yes"> Yes</label>
                                </div>
                                <div style="padding-left: 1em;">
                                    <input type="checkbox" id="no" name="no" value="" @if($contact->symptoms_recent == 0) checked readonly @else disabled @endif/>
                                    <label for="no"> No</label>  
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="50%">
                        <div>
                            <p>3.2. If No, had the contact had any symptoms related to
                                COVID-19 any time after exposure with the case
                            </p>
                            <div style="display: flex; flex-direction: row;">
                                <div style="padding-left: 1em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->symptoms_two_weeks == 1) checked readonly @else disabled @endif>
                                    <label for="Yes"> Yes</label>
                                </div>
                                <div style="padding-left: 1em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->symptoms_two_weeks == 0) checked readonly @else disabled @endif>
                                    <label for="No"> No</label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>If answer to 3.1 or 3.2 is Yes,</b><br>
                        <b>Date of Onset of First set of Symptoms: {{ $contact->date_of_onset_of_first_symptom }}</b><br>
                        <b>Check any and all applicable symptoms listed below</b>
                        <div>
                            <?php
                                $symptoms = $contact->symptoms ? json_decode($contact->symptoms) : [];  
                            ?>

                            <div class="list-symptoms">
                                <div class="row col-md-12">
                                    <div class="symptoms-1 col-md-3">
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="1" @if(in_array(4, $symptoms)) checked @endif readonly>
                                            <label for="Fever">Fever</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="2" @if(in_array(6, $symptoms)) checked @endif readonly>
                                            <label for= "Cough">Cough</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="3" @if(in_array(5, $symptoms)) checked @endif readonly>
                                            <label for= " General weakness / Tiredess"> General weakness / Tiredess</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="4" @if(in_array(19, $symptoms)) checked @endif readonly>
                                            <label for= "Headache">Headache</label>
                                        </div>
                                    </div>
                                    <div class="symptoms-3 col-md-3">
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="9" @if(in_array(13, $symptoms)) checked @endif readonly>
                                            <label for= "Pain in the muscles">Pain in the muscles</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="10" @if(in_array(7, $symptoms)) checked @endif readonly>
                                            <label for= "Sore Throat">Sore Throat</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="11" @if(in_array(8, $symptoms)) checked @endif readonly>
                                            <label for= "Runny nose">Runny nose</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="12" @if(in_array(9, $symptoms)) checked @endif readonly>
                                            <label for= "Shortness of breath">Shortness of breath</label>
                                        </div>
                                    </div>
                                    <div class="symptoms-5 col-md-3">
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="17" @if(in_array(18, $symptoms)) checked @endif readonly>
                                            <label for= "Nausea / Vomiting / Loss of appetite">Nausea / Vomiting / Loss of appetite</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="18" @if(in_array(17, $symptoms)) checked @endif readonly>
                                            <label for= "Diarrhea">Diarrhea</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="19" @if(in_array(10, $symptoms)) checked @endif readonly>
                                            <label for= "Irritability / Confusion">Irritability / Confusion</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="20" @if(in_array(12, $symptoms)) checked @endif readonly>
                                            <label for= "Recent loss of smell">Recent loss of smell</label>
                                        </div>
                                    </div>
                                    <div class="symptoms-6 col-md-3">
                                        <div>
                                            <input type="checkbox" name="symptoms[]" value="21" @if(in_array(11, $symptoms)) checked @endif readonly>
                                            <label for= "Recent loss of taste">Recent loss of taste</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="specify" name="specify" value="" @if(!is_null($contact->symptoms_specific)) checked @endif readonly/>
                                            <label for="specify"> Others, specify: <span style="font-weight: 500 !important;">{{ $contact ? $contact->symptoms_specific : '' }}</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">4. Contact pre-existing condition(s)</th>
                </tr>
                <tr>
                    <td>
                        <div class="col-md-6">
                            <?php
                                $symptomsComorbidity = $contact->symptoms_comorbidity ? json_decode($contact->symptoms_comorbidity) : [];
                            ?>
                            <div class="comorbidity"> 
                                <div class="comorbidity1 col-md-6">
                                    <div>
                                        <input type="checkbox" id="recent-loss-of-smell" name="recent-loss-of-smell" value="" @if(in_array(5, $symptomsComorbidity) || in_array(16, $symptomsComorbidity) || in_array(17, $symptomsComorbidity)) checked @endif readonly/>
                                        @if(in_array(5, $symptomsComorbidity))
                                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>One</u> )</label>
                                        @elseif(in_array(16, $symptomsComorbidity))
                                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Two</u> )</label>
                                        @elseif(in_array(17, $symptomsComorbidity))
                                            <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Three</u> )</label>
                                        @else
                                        <label for="recent-loss-of-smell">Pregnancy (trimester: <u></u> )</label>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="checkbox" id="post-delivery" name="post-delivery" value="" @if(in_array(18, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="post-delivery">Post-delivery <span> (< 6 weeks) </span></label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="cardiovascular-disease" name="cardiovascular-disease" value="" @if(in_array(7, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="cardiovascular-disease">Cardiovascular disease, including hypertension</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="diabetes" name="diabetes" value="" @if(in_array(1, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="diabetes"> Diabetes</label>
                                    </div>
                                </div>
                                <div class="comorbidity2 col-md-6">
                                    <div>
                                        <input type="checkbox" id="malignancy" name="malignancy" value="" @if(in_array(14, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="malignancy"> Malignancy</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="COPD" name="COPD" value="" @if(in_array(19, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="COPD"> COPD</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="Chronic-Kidney-Diseases" name="Chronic-Kidney-Diseases" value="" @if(in_array(10, $symptomsComorbidity)) checked @endif readonly/>
                                        <label for="Chronic-Kidney-Diseases"> Chronic Kidney Diseases</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="malignancy" name="malignancy" value="" @if(!is_null($contact->symptoms_comorbidity_specific)) checked @endif readonly/>
                                        <label for="malignancy"> Others, specify: <span style="font-weight: 500 !important;">{{ $contact ? $contact->symptoms_comorbidity_specific : '' }}</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table style="margin-top: 1em;">
                <tr>
                    <th style="background-color:#8eaadb">5. Occupation</th>
                </tr>
                <tr>
                    <td>
                        <div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="health-worker" name="health-worker" value="" @if($contact->occupation == '1') checked @endif>
                                <label for="health-worker"> Front Line Health Worker</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="working-with-animals" name="working-with-animals" value="" @if($contact->occupation == '2') checked @endif>
                                <label for="working-with-animals"> Doctor</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="health-lab-worker" name="health-lab-worker" value="" @if($contact->occupation == '3') checked @endif>
                                <label for="health-lab-worker"> Nurse</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="student-teacher" name="student-teacher" value="" @if($contact->occupation == '4') checked @endif>
                                <label for="student-teacher"> Police/Army</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="security-personnel" name="security-personnel" value="" @if($contact->occupation == '5') checked @endif>
                                <label for="security-personnel"> Business/Industry</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="waste-management-worker" name="waste-management-worker" value="" @if($contact->occupation == '6') checked @endif>
                                <label for="waste-management-worker"> Teacher/Student/Education</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="hotel-resturant" name="hotel-resturant" value="" @if($contact->occupation == '7') checked @endif>
                                <label for="hotel-resturant"> Journalist</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="other-specify" name="other-specify" value="" @if($contact->occupation == '8') checked @endif>
                                <label for="other-specify"> Agriculture</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="security-personnel" name="security-personnel" value="" @if($contact->occupation == '9') checked @endif>
                                <label for="security-personnel"> Transport/Delivery</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="security-personnel" name="security-personnel" value="" @if($contact->occupation == '0') checked @endif>
                                <label for="security-personnel"> Other</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <p>For each occupation, please specify location or facility:  <u>{{ $contact->occupaton_other }}</u> </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="2" class style="background-color:#8eaadb">6. General exposure information</th>
                </tr>
                <tr>
                    <td width="50%">Has the contact travelled in last 14 days</td>
                    <td width="50%">
                        <div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value=""  @if($contact->travelled_14_days == 1) checked readonly @else disabled @endif>
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value=""  @if($contact->travelled_14_days == 0) checked readonly @else disabled @endif>
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value=""  @if($contact->travelled_14_days == 2) checked readonly @else disabled @endif>
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel: {{ $contact->date_14_days }}</p>
                            <?php
                                $modes_of_travel = '';
                                if( $contact->modes_of_travel == '1'){
                                    $modes_of_travel = 'Air';
                                }elseif( $contact->modes_of_travel == '2'){
                                    $modes_of_travel = 'Public Transport';
                                }elseif( $contact->modes_of_travel == '3'){
                                    $modes_of_travel = 'Private Vehicle';
                                }elseif( $contact->modes_of_travel == '0'){
                                    $modes_of_travel = 'Other';
                                }
                            ?>
                            <p>Mode of travel: {{ $modes_of_travel }}</p>
                            <p>Place visited: <u>{{ $contact->travel_place }}</u> </p>
                        </div>
                        <div>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%">In the past 14 days, has the contact had contact
                        with anyone with suspected or confirmed COVID-19
                        infection?
                    </td>
                    <td width="50%">
                        <div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value=""  @if($contact->contact_status == 1) checked readonly @else disabled @endif>
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value=""  @if($contact->contact_status == 0) checked readonly @else disabled @endif>
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value=""  @if($contact->contact_status == 2) checked readonly @else disabled @endif>
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel (dd/mm/yyyy):{{ $contact->contact_last_date }}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%">Has the contact visited social
                        gatherings/meetings/events/temples/markets/halls
                        etc.
                    </td>
                    <td width="50%">
                        <div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="" @if($contact->contact_social_status == 1) checked readonly @else disabled @endif>
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value=""  @if($contact->contact_social_status == 0) checked readonly @else disabled @endif>
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value=""  @if($contact->contact_social_status == 2) checked readonly @else disabled @endif>
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel (dd/mm/yyyy):{{ $contact->contact_social_last_date }}</p>
                        </div>
                    </td>
                </tr>
            </table>

            <table style="margin-top: 1em;">
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">7. Exposure information (only fill the section if the contact is a health care worker)</th>
                </tr>
                <tr>
                    <td width="50%">
                        <p>Job title</p>
                        <p>(specify): {{ $contact->healthworker_title }}</p>
                    </td>
                    <td width="50%">
                        <p>Name of the work place: <u>{{ $contact->healthworker_workplace }}</u> </p>
                        <?php
                            $healthworker_station = '';
                            if($contact->healthworker_station == '1'){
                                $healthworker_station = 'Fever Clinic';
                            }elseif($contact->healthworker_station == '2'){
                                $healthworker_station = 'Isolation Ward';
                            }elseif($contact->healthworker_station == '3'){
                                $healthworker_station = 'ICU/Ventilator';
                            }elseif($contact->healthworker_station == '4'){
                                $healthworker_station = 'Lab';
                            }elseif($contact->healthworker_station == '0'){
                                $healthworker_station = 'Others';
                            }
                        ?>
                        <p>Station: Fever Clinic/ Isolation ward/ ICU/ Lab/ Other
                            (specify) <u>{{ $contact->healthworker_station_other }}</u>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Was appropriate PPE used?</p>
                        <div>
                            <div class="col-md-1" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="" @if($contact->healthworker_ppe == 1) checked readonly @else disabled @endif>
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-1" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value="" @if($contact->healthworker_ppe == 0) checked readonly @else disabled @endif>
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-10" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="" @if($contact->healthworker_ppe == 2) checked readonly @else disabled @endif>
                                <label for="unknown"> Unknown</label>
                            </div>
                            <div class="col-md-12" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> If No, Specify <u>___</u> </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date of first contact:</td>
                    <td>Date of last contact: {{ $contact->healthworker_last_date }}</td>
                </tr>
                <tr>
                    <td colspan="2">Any relevant narrative: {{ $contact->healthworker_narrative }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="col-md-6">Based on the exposure history, classification of the contact: </p>
                        <div class="col-md-1" style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Close" name="Close" value="">
                            <label for="Close"> Close</label>
                        </div>
                        <div class="col-md-1" style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Casual" name="Casual" value="">
                            <label for="Casual"> Casual</label>
                        </div>
                    </td>
                </tr>
            </table>

            <table style="margin-top: 1em;">
                <tbody>
                <tr>
                    <th  colspan="7" style="background-color:#8eaadb">6. Vaccination status</th>
                </tr>
                <tr>
                    <th width="30%" colspan="2">Has the Case under Investigation
                        received SARS-CoV-2 vaccine (COVID-19
                        vaccine)?
                    </th>
                    <th width="20%"><input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="" @if($contact->sars_cov2_vaccinated == 1) checked readonly @else disabled @endif>Yes</th>
                    <th colspan="2"><input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="" @if($contact->sars_cov2_vaccinated == 0) checked readonly @else disabled @endif>No</th>
                    <th colspan="2"><input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="" @if($contact->sars_cov2_vaccinated == 2) checked readonly @else disabled @endif>Unknown</th>
                </tr>
                <tr>
                    <td colspan="2" >If <b>Yes,</b>Name of the Vaccine(Product/Brand name)</td>
                    <th >Date of Vaccination(dd/mm/yyyy)</th>
                    <td colspan="4">Source of Information (check multiple options if needed)</td>
                </tr>
                <tr>
                    <td colspan="2">(Product/Brand name) ______</td>
                    <td>(dd/mm/yyyy)___/___/_____</td>
                    <td>Vaccination Card</td>
                    <td>Vaccination Register</td>
                    <td>Recall</td>
                    <td>Others</td>
    
                </tr>
                <tr>
                    <td width="2%">Dose 1</td>
                    <td>{{ $contact->dose_one_name ? $contact->vaccine->name : '' }}</td>
                    <td>{{ $contact->dose_one_date }}</td>
                    <td style="display: flex; justify-content: center;">
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Dose 2</td>
                    <td>{{ $contact->dose_one_name ? $contact->vaccine->name : '' }}</td>
                    <td>{{ $contact->dose_two_date }}</td>
                    <td style="display: flex; border: none; justify-content: center; ">
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                    <td>
                        <span>
                            <input type="checkbox" id="yes" name="yes" value="">
                            <label for="yes">yes</label>
                        </span>
                        <span style="padding-left: 0.2em;">
                            <input type="checkbox" id="no" name="no" value="">
                            <label for="no">no</label>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>

            <table style="margin-top: 1em;">
                <tbody>
                <tr>
                    <th style="background-color:#8eaadb">7. Contact Management</th>
                </tr>
                <tr>
                    <td>
                        <p>Measures taken:</p>
                        <div class="col-md-6">
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="contact-admitted" name="contact-admitted" value="" @if($contact->measures_taken == 1) checked readonly @else disabled @endif>
                                <label for="contact-admitted"> Contact admitted to hospital</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="quarantine-center" name="quarantine-center" value="" @if($contact->measures_taken == 2) checked readonly @else disabled @endif>
                                <label for="quarantine-center">Referred to Quarantine Center</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="home-quarantine" name="home-quarantine" value="" @if($contact->measures_taken == 3) checked readonly @else disabled @endif>
                                <label for="home-quarantine"> Home Quarantine</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="contact-lost" name="contact-lost" value="" @if($contact->measures_taken == 4) checked readonly @else disabled @endif>
                                <label for="contact-lost"> Contact lost</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="other-specify" name="other-specify" value="">
                                <label for="other-specify"> Other (specify) {{ $contact->measures_taken_other }}</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-md-12">
                            <p>If referred to hospital/quarantine facility:</p>
                            <p>Referred date: {{ $contact->measures_referral_date }}</p>
                            <p>Name of hospital/Quarantine Centre: {{ $contact->measures_hospital_name }}</p>
                            <p>Location:____</p>
                        </div>
                        <div>
                            <div class="col-md-6">
                                <p>Province:</p>
                                <p>Municipality</p>
                            </div>
                            <div class="col-md-6">
                                <p>District</p>
                                <p>Ward no.</p>
                            </div>
                        </div>
    
                    </td>
                </tr>
                </tbody>
            </table>

            <table>
                <tr>
                    <th style="background-color:#8eaadb">8. Test Status of the Contact</th>
                </tr>
                <tr>
                    <td>
                        <div class="col-md-12">
                            <p>Was the contact tested?</p>
                            <div>
                                <div class="col-md-1" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->test_status == 1) checked readonly @else disabled @endif>
                                    <label for="Yes"> Yes</label>
                                </div>
                                <div class="col-md-1" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->test_status == 0) checked readonly @else disabled @endif>
                                    <label for="No"> No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p>If yes, test conducted</p>
                            <div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="RT-PCR" name="RT-PCR" value="" @if($contact->test_type == 1) checked readonly @else disabled @endif>
                                    <label for="RT-PCR"> RT-PCR</label>
                                </div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="RDT- Antigen " name="RDT- Antigen " value="" @if($contact->test_type == 2) checked readonly @else disabled @endif>
                                    <label for="RDT- Antigen "> RDT- Antigen test</label>
                                </div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="" @if($contact->test_type == 0) checked readonly @else disabled @endif>
                                    <label for="unknown"> Unknown</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p>If yes, date of swab collection: <b>{{ $contact->collection_date }}</b></p>
                            <div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="test-result" name="test-result" value="">
                                    <label for="test-result"> Test Results:</label>
                                </div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="RT-PCR" name="RT-PCR" value="" @if($contact->test_type == 1) checked readonly @else disabled @endif>
                                    <label for="RT-PCR"> RT-PCR</label>
                                </div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="RDT- Antigen " name="RDT- Antigen " value="" @if($contact->test_type == 2) checked readonly @else disabled @endif>
                                    <label for="RDT- Antigen "> RDT- Antigen test</label>
                                </div>
                                <div class="col-md-2" style="padding-left: 0.5em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="" @if($contact->test_type == 0) checked readonly @else disabled @endif>
                                    <label for="unknown"> Unknown</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p>If positive, test result date: <b>{{ $contact->result_date }}</b></p>
                        </div>
                    </td>
                </tr>
            </table>
            
            <table style="margin-top: 1em;">
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">Final contact classification at final follow-up – Only for use by contact follow-up team </th>
                </tr>
                <tr>
                    <td width="50%">Please mark</td>
                    <td width="50%">
                        <div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="never-ill" name="never-ill" value="">
                                <label for="never-ill">  Never ill/not a case</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="confirmed-secondary" name="confirmed-secondary" value="">
                                <label for="confirmed-secondary"> Confirmed secondary case</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="lost-to-followup" name="lost-to-followup" value="">
                                <label for="lost-to-followup"> Lost to follow-up</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="suspected-case" name="suspected-case" value="">
                                <label for="suspected-case"> Suspected case</label>
                            </div>
                            <div style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="probable-case" name="probable-case" value="">
                                <label for="probable-case"> Probabale Case</label>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <table style="margin-top: 1em;">
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">11. Data collector information</th>
                </tr>
                <tr>
                    <td width="50%">Name: {{ $contact->checkedBy ? $contact->checkedBy->name : '' }}</td>
                    <td width="50%">Institution: {{ $contact->checkedBy ? $contact->checkedBy->getHealthpost($contact->hp_code) : '' }}</td>
                </tr>
                <tr>
                    <td>Telephone number:{{ $contact->checkedBy ? $contact->checkedBy->phone : '' }}</td>
                    <td>Email: {{ $contact->checkedBy ? $contact->checkedBy->user->email : '' }}</td>
                </tr>
                <tr>
                    <td>Form completion date: {{ $contact->completion_date }}</td>
                    <td></td>
                </tr>
            </table>
        </div>
        @if($contact->followUp && $contact->followUp->count())
        <div class="form-b2">
            <h4>Annex 3: Form B2 – Contact Follow-up Form/Symptoms Diary {{ ++$follow_count }}</h4>
            <table style="margin-top: 1em;">
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">1. Case Information</th>
                </tr>
                <tr>
                    <td>Name of the case: <u>{{ $data->name }}</u></td>
                    <td>EPID ID: <u>{{ $data->case_id }}</u></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th colspan="2" style="background-color:#8eaadb">2. Contact Information</th>
                </tr>
                <tr>
                    <td width="75%">Name <u>{{ $contact->name }}</u></td>
                    <td width="25%">EPID ID  <u>{{ $contact->followUp->case_id }}</u></td>
                </tr>
            </table>
            <table style="margin-top: 1em; ">
                <tr>
                    <th rowspan="2">No symptoms (check if none experienced)</th>
                    <th rowspan="2">Days to follow up*</th>
                    <th rowspan="2">Date of follow up (dd/mm/yy)</th>
                    <th style="text-align: center;" colspan="8">Symptoms**</th>
                </tr>
                <tr>
                    <th>Days since last contact with the case</th>
                    <th>Fever ≥38 °C</th>
                    <th>Runny nose</th>
                    <th>Cough</th>
                    <th>Sorethroat</th>
                    <th>Shortness of breath</th>
                    <th>Other symptoms: specify</th>
                </tr>
                <?php 
                for($i=0; $i<11; $i++){
                ?>
                <tr>
                    <td style="text-align: center;">{{ $i }}</td>
                    <td style="text-align: center;">
                        <p>{{ 10 - $i }}</p>
                        <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                    </td>
                    <td>{{ $contact->followUp->{'date_of_follow_up_'.$i} }}</td>
                    <td>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="" @if($contact->followUp->{'no_symptoms_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="none"> None</label>
                        </div>
                    </td>
                    <td>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->followUp->{'fever_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="Yes"> Yes</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->followUp->{'fever_'.$i} == '0') checked readonly @else disabled @endif>
                            <label for="No"> No</label>
                        </div>
                    </td>
                    <td>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->followUp->{'runny_nose_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="Yes"> Yes</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->followUp->{'runny_nose_'.$i} == '0') checked readonly @else disabled @endif>
                            <label for="No"> No</label>
                        </div>
                    </td>
                    <td width="7%">
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->followUp->{'cough_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="Yes"> Yes</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->followUp->{'cough_'.$i} == '0') checked readonly @else disabled @endif>
                            <label for="No"> No</label>
                        </div>
                    </td>
                    <td>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->followUp->{'sore_throat_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="Yes"> Yes</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->followUp->{'sore_throat_'.$i} == '0') checked readonly @else disabled @endif>
                            <label for="No"> No</label>
                        </div>
                    </td>
                    <td>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="" @if($contact->followUp->{'breath_'.$i} == '1') checked readonly @else disabled @endif>
                            <label for="Yes"> Yes</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="" @if($contact->followUp->{'breath_'.$i} == '0') checked readonly @else disabled @endif>
                            <label for="No"> No</label>
                        </div>
                    </td>
                    <td>{{ $contact->followUp->{'symptoms_other_'.$i} }}</td>
                </tr>
                <?php } ?>
            </table>
            <div style="margin-top: 0.5em; margin-bottom: 1em;" class="col-md-12">
                <p>* Follow-up should start from the day it has been since last contact with the case. For e.g., if the contact has not been in contact with the case
                    since 12 days, the follow-up should start from the 12th day in the column “Days to follow up”
                </p>
                <p>** Please select None for No symptoms. If no symptoms are experienced, then consider the entry comple</p>
            </div>
        </div>
        @endif

        @endforeach
        @endif
    </div>
</div>
@endsection
@section('style')
<style>
    *,
    body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
    }

    p,b {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 14px;
    }



    header {
        display: flex!important;
        flex-direction: row;
        justify-content: center;
        align-self: center;
        padding: 10px;
        background-color: #1e7eb8;
    }

    header>img {
        display: flex;
        justify-content: start;
        align-items: flex-start;
        margin-right: auto;
    }

    .header-title {
        line-height: 17px;
        text-align: center;
        color: #fff;
        font-weight: 600;
    }

    .side-header {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .side-header {
        color: #fff;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-self: center;
        margin-left: auto;
    }

    h3 {
        margin: 1em 0 1em 0;
        text-align: center;
        color: #1c609d;
    }

    .investigation-form>form {
        display: flex;
        font-size: 14px;
    }

    .investigation-form>form>input,
    p {
        margin-left: 20px;
    }

    .info {
        margin-top: 1em;
        line-height: 20px;
        font-size: 14px;
    }

    .section-1,
    .section-2 {
        margin: 10px
    }

    .section-1>h5 {
        font-size: 16px;
        color: #fff;
        background-color: #2f5496;
        padding: 0.7em;
    }

    .date {
        display: flex;
        display: row;
    }

    .pi-1 {
        line-height: 20px;
    }

    .pi-2 {
        line-height: 20px;
    }

    .info-personal {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-self: center;
        margin-top: 0.7em;
    }

    .gender {
        display: flex;
        display: row;
    }

    .pi-2 {
        margin-left: auto;
    }

    sub-section>h5 {
        padding: 0.4em;
        margin-top: 0.7em;
        background-color: #dfdfdfdf;
    }

    table {
        width: 100%;
    }

    td {
        font-size: 14px;
        padding: 5px !important;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
    }

    .isolatedAt {
        line-height: 25px;
    }

    .detail-checkbox {
        display: flex;
        flex-direction: row;
    }

    .detail-checkbox>input {
        margin-left: 0.5em;
    }

    .detail-checkbox {
        padding-top: 1em;
    }

    .section-2>h5 {
        font-size: 16px;
        color: #fff;
        background-color: #2f5496;
        padding: 0.7em;
    }

    .section-2>p {
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        font-size: 14px;
        padding: 0.5em;
        margin: 0.7em!important;
        background-color: rgb(233, 233, 233);
    }

    .list-symptoms {
        display: flex;
        flex-direction: row;
    }

    .list-symptoms>.symptom-1 {
        display: flex;
        flex-direction: row;
    }

    .comorbidity1{
        display: flex;
        flex-direction: column;
    }

    .high-exposures>b {
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        font-size: 14px;
        padding: 0.5em;
        margin: 0.7em!important;
        background-color: rgb(233, 233, 233);
    }

    .high-exposure-title > p{
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        font-size: 14px;
        padding: 0.5em;
        margin: 0.7em!important;
        background-color: rgb(233, 233, 233);
    }

    .travel-head {
        display: flex;
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        font-size: 14px;
        padding: 0.5em;
        margin: 0.7em 0 !important;
        background-color: rgb(233, 233, 233);
    }

    .travel-checkbox{
        display: flex;
        margin-left: auto;
    }

    .checkbox-tra{
        padding-left: 1em;
    }

    .foreign-domestic-table{
        margin-top: 0.5em;
        margin-bottom: 1em;
    }

    th, td{
        padding: 5px !important;
    }

    .source-title{
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        font-size: 14px;
        padding: 0.5em;
        margin: 0.7em!important;
        background-color: rgb(233, 233, 233);
    }

    .source-body{
        border: 1px soild #000;
    }

    .information{
        margin-top : 1em;
    }

    .information-head {
        display: flex;
        margin-top: 1em;
        padding: 0.5em;
        background-color: #e7e6e6;
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
    }

    .box-body {
        border-bottom: 1px solid black;
    }

    .box {
        padding: 5px;
        border: 1px solid black;
        margin-top: 0.7em;
    }

    .box-head {
        border-bottom: 1px solid black;
        padding: 5px;
    }

    .vacc-status-title{
        display: flex;
        margin-top: 1em;
        padding: 0.5em;
        background-color: #e7e6e6;
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
    }

    .information-close-title > p{
        display: flex;
        margin-top: 1em !important;
        padding: 0.5em !important;
        background-color: #e7e6e6;
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
    }

    .section-3>h5 {
        font-size: 16px;
        color: #fff;
        background-color: #2f5496;
        padding: 0.7em;
    }

    .section-4>h5 {
        font-size: 16px;
        color: #fff;
        background-color: #2f5496;
        padding: 0.7em;
    }

    .form-b1 > h4{
        margin: 2em 0 1em 0;
        color: #23497e;
        text-align: center;
    }

    .form-b2 > h4 {
        margin: 2em 0 1em 0;
        color: #23497e;
        text-align: center;
    }

    .arrow-img{
        position: absolute;
        margin-top: -20px;
        z-index: -1;
    }
</style>
@endsection
