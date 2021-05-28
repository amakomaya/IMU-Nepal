@extends('layouts.backend.app')
@section('content')
<style>
    input[type="checkbox"][readonly] {
    pointer-events: none;
    }
</style>
@php 
    $symptoms = json_decode($data->symptoms ?? []);
    $symptomscontactdetail= json_decode(isset($data->contactDetail) ? $data->contactDetail->symptoms : [], true);
    $symptomsComorbidity = json_decode(isset($data->symptoms_comorbidity) ? $data->symptoms_comorbidity : [], true);
    $symptomsComorbidityContactDetail = json_decode(isset($data->contactDetail) ? $data->contactDetail->symptoms_comorbidity : [] , true);
    $travelled_from = json_decode($data->travelled_where ?? '', true);
    $vaccineDosefirst = json_decode(isset($data->caseManagement) ? $data->caseManagement->first_source_info : [], true);
    $vaccineDosesecond = json_decode(isset($data->caseManagement) ? $data->caseManagement->second_source_info : [], true);
    $meansOfTravel = json_decode(isset($data->caseManagement) ? $data->caseManagement->travel_medium : [], true);
@endphp

<div id="page-wrapper">
    <header>
        <img src="{{ asset('images/v-card/gov_logo.png') }}" width="100" height="75" alt="" />
        <div class="header-title">   
            <p>Government of Nepal</p>
            <p>Ministry of Health and Population</p>
            <p>Department of Health Services</p>
            <p>Epidemiology and Disease Control Division</p>
        </div>
        <div class="side-header">Unique ID (in IMU app): {{ $data->case_id }}<u></u></div>
    </header>
    <div class="investigation-form">
        <h3>A Form: Investigation Form for Probable or Confirmed Case of COVID-19</h3>
        <form action="#">
            <div style="padding-left: 0.5em;">
                <input style="padding-left: 0.5em;" type="checkbox" id="probable-case" name="probable-case" value="" />
                <label for="probable-case"> Probable case</label>
            </div>
            <div style="padding-left: 0.5em;">
                <input style="padding-left: 0.5em;" type="checkbox" id="confirmed-case*" name="confirmed-case*" value="" />
                <label for="confirmed-case*"> Confirmed case*</label>
            </div>

            <p style="padding-left: 0.5em;">(*Please see Page 4 for Case Definitions)</p>
        </form>
        <div class="info">
            <p>Date of case received by health authority [dd/mm/yyyy]: <u></u></p>
            <p>Date of CICT initiated [dd/mm/yyyy]: <u></u></p>
            <p>Name and Address of the reporting Institution: <u></u></p>
        </div>
        <section class="section-1">
            <h5>Section 1: Personal Information</h5>
            <div class="info-personal">
                <div class="pi-1">
                    <p>Unique Identifier (Case Epi Id): <u></u></p>
                    <p>Father/mother’s name: <u></u></p>
                    <div class="date">
                        <p style="padding-right: 2em !important;">Age: <u>{{$data->age}}</u></p>
                        <p style="padding-right: 2em !important;">years: <u></u></p>
                        <p>months: <u></u></p>
                    </div>
                    <p>Contact number: <u>{{$data->emergency_contact_one}}</u></p>
                </div>
                <div class="pi-2">
                    <p>Name: <u>{{$data->name}}</u></p>
                    <div style="display: flex; flex-direction: row;">
                            <p>Sex:</p>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Male" name="Male" value="" @if(isset($data->contactDetail) && $data->contactDetail->sex == 2) checked readonly @else disabled @endif>
                                <label for="Male"> Male</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Female" name="Female" value="" @if(isset($data->contactDetail) && $data->contactDetail->sex == 1) checked readonly @else disabled @endif>
                                <label for="Female"> Female</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Unknown" name="Unknown" value="" @if(isset($data->contactDetail) && $data->contactDetail->sex == 3) checked readonly @else disabled @endif>
                                <label for="Unknown"> Unknown</label>
                            </div> 
                        </div>
                    <p>Nationality: <u></u></p>
                    <p>Alternate contact number: <u>{{$data->emergency_contact_two}}</u></p>
                </div>
            </div>
            <sub-section>
                <h5>Current Address</h5>
                <table>
                    <tr>
                        <td>Province: <u>{{$data->municipality->province_id}}</u></td>
                        <td colspan="2">District: <u>{{$data->municipality->district_name}}</u></td>
                    </tr>
                    <tr>
                        <td>
                            Municipality: <u>{{$data->municipality->municipality_name}}</u> <br />
                            <p>If information is given by any other than case,</p>
                            <p>Name of the informant: <u></u></p>
                        </td>
                        <td>
                            <p>Ward No: {{$data->ward}}</p>
                            <p>Relationship: <u></u></p>
                        </td>
                        <td>
                            <p>Tole/Landmark: {{$data-> tole}}</p>
                            <p>Contact no: {{$data->emergency_contact_one}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Case managed at:</b>
                            <div class="isolatedAt">
                                <b>Isolated at: </b>
                                <input type="checkbox" id="home" name="home" value="" />
                                <label for="home"> Home</label>
                                <input type="checkbox" id="institution" name="institution" value="" />
                                <label for="institution"> Institution <u></u></label>
                            </div>
                            <div class="isolatedAt">
                                <b>Admitted at: </b>
                                <input type="checkbox" id="hospital" name="hospital" value="" />
                                <label for="hospital"> Hospital <u></u></label>
                            </div>
                        </td>
                        <td colspan="2">
                            <b>Details:</b>
                            <div class="detail-checkbox">
                                <input type="checkbox" id="ward" name="ward" value="" />
                                <label for="ward"> In Ward</label>
                                <input type="checkbox" id="ICU" name="ICU" value="" />
                                <label for="ICU"> In ICU <u></u></label>
                                <input type="checkbox" id="ventilator" name="ventilator" value="" />
                                <label for="ventilator"> On Ventilator <u></u></label>
                            </div>
                            <b>Date of Admission: <u></u>dd/ <u></u> mm / <u></u> yyyy</b>
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
                    <input type="checkbox" id="no" name="no" value="" @if($data->symptoms_recent != 1) checked readonly @else disabled @endif/>
                    <label for="no"> No</label>      
                </p>
                <p>
                    2.2 If no, whether symptomatic anytime during the past 2 weeks
                    <input type="checkbox" id="yes" name="yes" value="" @if($data->symptoms_within_four_week == 1) checked readonly @else disabled @endif/>
                    <label for="yes"> Yes</label>
                    <input type="checkbox" id="no" name="no" value="" @if($data->symptoms_within_four_week != 1) checked readonly @else disabled @endif/>
                    <label for="no"> No <u></u></label>
                </p>
                <p>
                    If answer to 2.1 or 2.2 is Yes, Date of Onset of First set of Symptoms [dd/mm/yyyy]  <u style="padding: 0 0.7em !important;">{{$data->date_of_onset_of_first_symptom}}</u> and check any and all applicable symptoms listed below:
                </p>
                <div class="list-symptoms">

                    <div class="symptoms-1 col-md-4">
                        <div>
                            <input type="checkbox" id="Fever" name="Fever" value="t" @if(in_array(4, $symptoms)) checked @endif readonly/>
                            <label for="Fever">Fever</label>                                           
                        </div>
                        <div>
                            <input type="checkbox" id="muscles" name="muscles" value="" @if(in_array(13, $symptoms)) checked @endif readonly/>
                            <label for="muscles">Pain in the muscles</label>
                        </div>
                        <div>
                            <input type="checkbox" id="" name="" value="" readonly @if(in_array(13, $symptoms)) checked @endif readonly/>
                            <label for="">Nausea / Vomiting / Loss of appetite </label>
                        </div>
                        <div>
                            <input type="checkbox" id="lossoftaste" name="lossoftaste" value="" @if(in_array(11, $symptoms)) checked @endif readonly/>
                            <label for="lossoftaste"> Recent loss of taste</label>
                        </div>
                    </div>
                    <div class="symptoms-2 col-md-2">
                        <div>
                            <input type="checkbox" id="Cough" name="Cough" value="" @if(in_array(6, $symptoms)) checked @endif readonly/>
                            <label for="Cough"> Cough</label>
                        </div>
                        <div>
                            <input type="checkbox" id="Sorethroat" name="Sorethroat" value="" @if(in_array(7, $symptoms)) checked @endif readonly/>
                            <label for="Sorethroat"> Sore throat</label>
                        </div>
                        <div>
                            <input type="checkbox" id="Diarrhoea" name="Diarrhoea" value="" @if(in_array(17, $symptoms)) checked @endif readonly/>
                            <label for="Diarrhoea"> Diarrhoea</label>
                        </div>
                    </div>
                    <div class="symptoms-3 col-md-3">
                        <div>
                            <input type="checkbox" id="Tiredness" name="Tiredness" value="" @if(in_array(5, $symptoms)) checked @endif readonly/>
                            <label for="Tiredness"> General weakness/Tiredness</label>
                        </div>
                        <div>
                            <input type="checkbox" id="runnynose" name="runnynose" value="" @if(in_array(8, $symptoms)) checked @endif readonly/>
                            <label for="runnynose"> Runny nose </label>
                        </div>
                        <div>
                            <input type="checkbox" id="Irritability/Confusion" name="Irritability/Confusion" value="" @if(in_array(10, $symptoms)) checked @endif readonly/>
                            <label for="Irritability/Confusion"> Irritability/Confusion</label>
                        </div>
                        <div>
                            <input type="checkbox" id="specify" name="specify" value="" @if(!is_null($data->symptoms_specific)) checked @endif readonly/>
                            <label for="specify"> Others, specify: <span style="font-weight: 500 !important;">{{$data->symptoms_specific}}</span></label>
                        </div>
                    </div>
                    <div class="symptoms-4 col-md-3">
                        <div>
                            <input type="checkbox" id="Headache" name="Headache" value="" @if(in_array(19, $symptoms)) checked @endif readonly/>
                            <label for="Headache"> Headache</label>
                        </div>
                        <div>
                            <input type="checkbox" id="shortbreath" name="shortbreath" value="" @if(in_array(9, $symptoms)) checked @endif readonly/>
                            <label for="shortbreath"> Shortness of breath</label>
                        </div>
                        <div>
                            <input type="checkbox" id="recent-loss-of-smell" name="recent-loss-of-smell" value="" @if(in_array(12, $symptoms)) checked @endif readonly/>
                            <label for="recent-loss-of-smell"> Recent loss of smell</label>
                        </div>
                    </div>
                </div>
            </div>
            <p>II. Underlying medical conditions or disease / comorbidity (check all that apply):</p>
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
                        <input type="checkbox" id="post-delivery" name="post-delivery" value="" />
                        <label for="post-delivery">Post-delivery <span> (<6 weeks) </span></label>
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
                        <input type="checkbox" id="COPD" name="COPD" value="" @if(in_array(15, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="COPD"> COPD</label>
                    </div>
                    <div>
                        <input type="checkbox" id="Chronic-Kidney-Diseases" name="Chronic-Kidney-Diseases" value="" @if(in_array(10, $symptomsComorbidity)) checked @endif readonly/>
                        <label for="Chronic-Kidney-Diseases"> Chronic Kidney Diseases</label>
                    </div>
                    <div>
                        <input type="checkbox" id="malignancy" name="malignancy" value="" @if(!is_null($data->symptoms_comorbidity_specific)) checked @endif readonly/>
                        <label for="malignancy"> Others, specify: <span style="font-weight: 500 !important;">{{$data->symptoms_comorbidity_specific}}</span></label>
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
                    
                        <input type="checkbox" id="health-care" name="health-care" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 1) checked readonly @else disabled @endif/>
                        <label for="health-care"> Health Care Work (any type, level & facility, including cleaning staff)</label>
                    </div>
                    <div>
                        <input type="checkbox" id="community-health" name="community-health" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 2) checked readonly @else disabled @endif/>
                        <label for="community-health"> Community Health / Immunization Clinic Volunteer</label>
                    </div>
                    <div>
                        <input type="checkbox" id="sanitary" name="sanitary" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 3) checked readonly @else disabled @endif/>
                        <label for="sanitary"> Sanitary/Waste Collection/Management Worker/Transport Driver/Helper</label>
                    </div>
                    <div>
                        <input type="checkbox" id="patient-dead-body" name="shortbreath" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 4) checked readonly @else disabled @endif/>
                        <label for="shortbreath"> Patient & Dead body Transport Driver/Helper </label>
                    </div>
                    <div>
                        <input type="checkbox" id="management-work" name="management-work" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 5) checked readonly @else disabled @endif/>
                        <label for="management-work"> Dead body management work</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="old-age-home" name="old-age-home" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 6) checked readonly @else disabled @endif/>
                            <label for="old-age-home"> Old Age Home/Care work </label>
                        </div>
                        <div>
                            <input type="checkbox" id="border-crossing" name="border-crossing" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 7) checked readonly @else disabled @endif/>
                            <label for="border-crossing"> Border Crossing / Point of Entry Staff </label>
                        </div>
                        <div>
                            <input type="checkbox" id="journalist" name="journalist" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 12) checked readonly @else disabled @endif/>
                            <label for="journalist"> Journalist </label>
                        </div>
                        <div>
                            <input type="checkbox" id="prisoner" name="prisoner" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 15) checked readonly @else disabled @endif/>
                            <label for="prisoner"> Prisoner </label>
                        </div>
                        <div>
                            <input type="checkbox" id="elected-representative" name="elected-representative" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 18) checked readonly @else disabled @endif />
                            <label for="elected-representative"> Local body Elected Representative </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="security-staff" name="security-staff" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 8) checked readonly @else disabled @endif/>
                            <label for="security-staff"> Any Security Staff</label>
                        </div>
                        <div>
                            <input type="checkbox" id="hotel-restaurant" name="hotel-restaurant" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 9) checked readonly @else disabled @endif/>
                            <label for="hotel-restaurant"> Hotel/Restaurant/Bar work</label>
                        </div>
                        <div>
                            <input type="checkbox" id="migrant" name="migrant" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 13) checked readonly @else disabled @endif />
                            <label for="migrant"> Migrant </label>
                        </div>
                        <div>
                            <input type="checkbox" id="teacher" name="teacher" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 16) checked readonly @else disabled @endif/>
                            <label for="teacher"> Teacher </label>
                        </div>
                        <div>
                            <input type="checkbox" id="bank-govt-office" name="bank-govt-office" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 19) checked readonly @else disabled @endif/>
                            <label for="bank-govt-office"> Bank/Govt Office / Public Corporation staff </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="farm-work" name="farm-work" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 10) checked readonly @else disabled @endif/>
                            <label for="farm-work"> Farm work</label>
                        </div>
                        <div>
                            <input type="checkbox" id="shop-worker" name="shop-worker" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 11) checked readonly @else disabled @endif/>
                            <label for="shop-worker"> Shop/Store worker</label>
                        </div>
                        <div>
                            <input type="checkbox" id="refugee" name="refugee" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 14) checked readonly @else disabled @endif/>
                            <label for="refugee"> Refugee </label>
                        </div>
                        <div>
                            <input type="checkbox" id="Student" name="Student" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 17) checked readonly @else disabled @endif/>
                            <label for="Student"> Old Age Home/Care work </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div>
                            <input type="checkbox" id="bank-govt-office" name="bank-govt-office" value="" @if(isset($data->caseManagement) && $data->caseManagement->high_exposure == 20) checked readonly @else disabled @endif/>
                            <label for="bank-govt-office"> UN / Development Partner / INGO / NGO Frontline worker</label>
                        </div>
                        <div>
                            <input type="checkbox" id="specify-other" name="specify-other" value="" @if(isset($data->caseManagement) && !is_null($data->caseManagement->high_exposure_other)) checked @endif readonly/>
                            <label for="specify-other"> Others (specify): {{$data->caseManagement->high_exposure_other}}</label>
                        </div>
                    </div>
                </div>
                <span>&nbsp;</span>
                <div class="travel">
                    <div class="travel-head">
                        <p>IV. Travel during 14 days before OR aftersymptom onset or date of sample collection for testing:</p>
                        <div class="travel-checkbox">
                            <div class="checkbox-tra">
                                <input type="checkbox" id="vehicle1" name="vehicle1" value="Yes" @if($data->travelled == 1) checked readonly @else disabled @endif>
                                <label for="vehicle1"> Yes </label>
                            </div>
                            <div class="checkbox-tra">
                                <input type="checkbox" id="vehicle1" name="vehicle1" value="No" @if($data->travelled != 1) checked readonly @else disabled @endif>
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
                                    <th style="background-color:#f0e3ca">Date of Departure from or to the place [dd/mm/yyyy]
                                    </th>
                                    <th style="background-color:#f0e3ca">Date of Arrival in Nepal or Current place of Residence [dd/mm/yyyy]
                                    </th>
                                    <th style="background-color:#f0e3ca">Mode of travel [ Air, Public Transport,Private Vehicle]</th>
                                    <th style="background-color:#f0e3ca">Flight/Vehicle No./ Bus Route / Driver Contact No.</th>
                                </tr>
                                <tr>
                                    <td>{{$data->caseManagement->departure}}</td>
                                    <td>{{$data->caseManagement->destination}}</td>
                                    <!-- <td style="background-color:#f0e3ca">{{$data->travelled_date}}</td> -->
                                    <td style="background-color:#f0e3ca">{{$data->caseManagement->travel_date}}</td>
                                    <td style="background-color:#f0e3ca"></td>
                                    <td style="background-color:#f0e3ca">
                                    
                                    @foreach ($meansOfTravel as $travel_means) 
                                        @if($travel_means == 1)   
                                            Air,
                                        @elseif($travel_means == 2)
                                            Taxi,
                                        @elseif($travel_means == 3)
                                            Bus/Micro,
                                        @elseif($travel_means == 4)
                                            Truck,
                                        @elseif($travel_means == 5)
                                            other,               
                                        @endif
                                    @endforeach
                                    </td>
                                    <td style="background-color:#f0e3ca">
                                    @if(isset($data->caseManagement) && !is_null($data->caseManagement->flight_no))
                                        <b>Flight:</b> {{$data->caseManagement->flight_no}},
                                    @endif
                                    @if(isset($data->caseManagement) && !is_null($data->caseManagement->vehicle_no))
                                        <b>Vehicle No.:</b> {{$data->caseManagement->vehicle_no}},
                                    @endif
                                    @if(isset($data->caseManagement) && !is_null($data->caseManagement->travel_route))
                                        <b>Bus Route:</b> {{$data->caseManagement->travel_route}},
                                    @endif
                                    </td>
                                </tr>
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
                            period: From {{isset($data->caseManagement) ? $data->caseManagement->reference_date_from : ''}} (dd/mm/yyyy) To {{isset($data->caseManagement) ? $data->caseManagement->reference_date_from : ''}} (dd/mm/yyyy)
                        </b>
                    </div>
                    <div class="box-body">
                        <p>Did any known case(s) of COVID-19 live in the same household as the case under investigation during the reference period? </p>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                    <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->caseManagement) && $data->caseManagement->anyother_member_household == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if(isset($data->caseManagement) && $data->caseManagement->anyother_member_household == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if(isset($data->caseManagement) && $data->caseManagement->anyother_member_household == 2) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="Unknown"> Unknown</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <p>If <b>Yes,</b> fill the details in the table below.</p>
                            </div>

                            <div class="col-md-4">
                                <p>Total household members: {{isset($data->caseManagement) ? $data->caseManagement->total_member : ''}}</p>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Phone no.</th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Ph.no</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case had close contact with probable and confirmed case/ person with travel history from COVID-19 affected place
                            during the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" />
                                    <label style="padding-left: 0.5em;" for="yes"> yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" />
                                    <label style="padding-left: 0.5em;" for="no"> no</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" />
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
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Ph.no</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case under investigation provide direct care to known case(s) of COVID-19 during the reference period?
                        </b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_direct_care == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_direct_care == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_direct_care == 2) checked readonly @else disabled @endif>
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
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Age(Yrs)</th>
                                <th>Sex</th>
                                <th>Ph.no</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <b>Did the case under investigation attend School/Workplace/hospitals/healthcare institution/ Social gathering(s) during the
                            reference period?
                        </b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_gone_institution == 1) checked readonly @else disabled @endif >
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                <input type="checkbox" id="no" name="no" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_gone_institution == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if(isset($data->caseManagement) && $data->caseManagement->case_gone_institution == 2) checked readonly @else disabled @endif>
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
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
                                    <input type="checkbox" id="yes" name="yes" value=""@if(isset($data->caseManagement) && $data->caseManagement->sars_cov2_vaccinated == 1) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="No" name="No" value="" @if(isset($data->caseManagement) && $data->caseManagement->sars_cov2_vaccinated == 0) checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="No"> No</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="" @if(isset($data->caseManagement) && $data->caseManagement->sars_cov2_vaccinated == 2)  checked readonly @else disabled @endif>
                                    <label style="padding-left: 0.5em;" for="unknown"> Unknown</label>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" rowspan="2">If <b>Yes,</b>Name of the Vaccine(Product/Brand name)</td>
                            <th rowspan="2">Date of Vaccination(dd/mm/yyyy)</th>
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
                            <td>{{isset($data->caseManagement) ? $data->caseManagement->first_product_name : ''}}</td>
                            <td>{{isset($data->caseManagement) ? $data->caseManagement->first_date_vaccination : ''}}</td>
                            <td style="display: flex; justify-content: center;"> 
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" @if(in_array(1, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" @if(!in_array(1, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" @if(in_array(2, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 2em;">
                                <input type="checkbox" id="no" name="no" value="" @if(!in_array(2, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                <label for="no">no</label>
                            </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" @if(in_array(3, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 3em;">
                                <input type="checkbox" id="no" name="no" value="" @if(!in_array(3, $vaccineDosefirst)) checked readonly @else disabled @endif/>
                                <label for="no">no</label>
                            </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->caseManagement) && !is_null($data->caseManagement->first_source_info_specific))  checked readonly @else disabled @endif/>
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 0.2em;">
                                <input type="checkbox" id="no" name="no" value=""  @if(isset($data->caseManagement) && is_null($data->caseManagement->first_source_info_specific))  checked readonly @else disabled @endif/>
                                <label for="no">no</label>
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dose 2</td>
                            <td>{{isset($data->caseManagement) ? $data->caseManagement->second_product_name : ''}}</td>
                            <td>{{isset($data->caseManagement) ? $data->caseManagement->second_date_vaccination : ''}}</td>
                            <td style="display: flex; border: none; justify-content: center; ">
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" @if(in_array(1, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" @if(!in_array(1, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" @if(in_array(2, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" @if(!in_array(2, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <input type="checkbox" id="yes" name="yes" value="" @if(in_array(3, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="yes">yes</label>
                                </span>
                                <span  style="padding-left: 2em;">
                                    <input type="checkbox" id="no" name="no" value="" @if(!in_array(3, $vaccineDosesecond)) checked readonly @else disabled @endif/>
                                    <label for="no">no</label>
                                </span>
                            </td>
                            <td>
                            <span>
                                <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->caseManagement) && !is_null($data->caseManagement->second_source_info_specific)) checked readonly @else disabled @endif/>
                                <label for="yes">yes</label>
                            </span>
                            <span  style="padding-left: 0.2em;">
                                <input type="checkbox" id="no" name="no" value="" @if(isset($data->caseManagement) && is_null($data->caseManagement->second_source_info_specific)) checked readonly @else disabled @endif/>
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
                            Reference period: From ______________ (dd/mm/yyyy) To _______________ (dd/mm/yyyy
                        </b><br>
                        <div>
                            <b>Reference period: From ______________ (dd/mm/yyyy) To _______________ (dd/mm/yyyy)</b>
                        </div>
                    </div>
                    <hr style="margin: 0; padding: 0;">
                    <div class="box-body">
                        <div>
                            <div class="col-md-6">
                                <b>Household Contacts during the reference period:</b>
                            </div>

                            <div class="col-md-6">
                                <p>Total household members: _______________</p>
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case under investigation travelled in public/ private vehicle in the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="">
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="">
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="">
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case under investigation provide direct care to anyone other than household contacts above in the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="">
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="">
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="">
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <b style="margin: 0.5em 0 !important;">Did the case travel or attend school/workplace/hospitals/health care institutions/social gathering(s) during the reference period?</b>
                        <div>
                            <div style="display: flex;" class="col-md-4">
                                <div style="display: flex;">
                                    <input type="checkbox" id="yes" name="yes" value="">
                                    <label style="padding-left: 0.5em;" for="yes"> Yes</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="no" name="no" value="" >
                                    <label style="padding-left: 0.5em;" for="no"> No</label>
                                </div>
                                <div style="display: flex; padding-left: 1em">
                                    <input type="checkbox" id="unknown" name="unknown" value="">
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
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
                        <input type="checkbox" id="yes" name="yes" value="" @if(isset($data->ancs()->first()->sample_type) && !is_null($data->ancs()->first()->sample_type)) checked readonly @else disabled @endif >
                        <label for="yes">yes</label>
                    </span>
                        <span class="col-md-3" style="padding-left: 2em;">
                        <input type="checkbox" id="no" name="no" value="" @if(isset($data->ancs()->first()->sample_type) && is_null($data->ancs()->first()->sample_type)) checked readonly @else disabled @endif>
                        <label for="no">no</label>
                    </span>
                    </td>
                    <td rowspan="2">{{ isset($data->ancs()->first()->labreport) ? $data->ancs()->first()->labreport->created_at : ''}}</td>
                    <td> <b>Date:</b> </td>
                    <td rowspan="2"></td>
                    <td rowspan="2"> {{isset($data->ancs()->first()->labreport) ? $data->ancs()->first()->labreport->sample_test_date : ''}} </td>
                    <td rowspan="2"> 
                        @if(isset($data->ancs()->first()->labreport))
                        @if($data->ancs()->first()->labreport->sample_test_result == 3)
                            Positive
                        @elseif($data->ancs()->first()->labreport->sample_test_result == 4)
                            Negative
                        @elseif($data->ancs()->first()->labreport->sample_test_result == 5)
                            Don't know
                        @elseif($data->ancs()->first()->labreport->sample_test_result == 6)
                            Rejected
                        @endif
                        @endif
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
                <td>Name: </td>
                <td>Telephone: </td>
                </tr>
                <tr>
                    <td>Institution: </td>
                    <td>Email:</td>
                </tr>
                <tr>
                    <td colspan="2">Form completion date (dd/mm/yyyy): <u>_____</u></td>
                </tr>
            </table>
        </section>
        <div class="form-b1">
            <h4>Form B1 - Contact Interview Form</h4>
            <table>
                <tr>
                    <th style="background-color:#8eaadb" colspan="2">1. Case Information</th>
                </tr>
                <tr>
                    <td class="col-md-6">
                        <b>Name of the Case: <u>{{ isset($data->contactDetail) ? $data->contactDetail->name : '' }}</u></b>
                    </td>
                    <td class="col-md-6">
                        <b>EPID ID</b>
                    </td>
                </tr>
                <tr>
                    <th style="background-color:#8eaadb" colspan="2">2. Personal details of the contact</th>
                </tr>
                <tr>
                    <td>EPID ID no</td>
                    <td>Name: {{ isset($data->contactDetail) ? $data->contactDetail->name : ''}}</td>
                </tr>
                <tr>
                    <td>Date of birth (dd/mm/yyyy)/Age <u>{{ isset($data->contactDetail) ? $data->contactDetail->age : ''}}</u></td>
                    <td>
                        <div style="display: flex; flex-direction: row;">
                            <p>Sex:</p>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Male" name="Male" value="" @if(isset($data->contactDetail) && $data->contactDetail->sex == 1) checked readonly @else disabled @endif>
                                <label for="Male"> Male</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Female" name="Female" value="" @if($data->sex == 2) checked readonly @else disabled @endif>
                                <label for="Female"> Female</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="Unknown" name="Unknown" value="" @if($data->sex == 3) checked readonly @else disabled @endif>
                                <label for="Unknown"> Unknown</label>
                            </div> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-md-6">
                        <b>Nationality <u></u></b>
                    </td>
                    <td class="col-md-6">
                        <b>Relation to the case:</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="col-md-6">
                            <p>Current Address: </p>
                            <p>Province: {{ isset($data->contactDetail) ? $data->contactDetail->province_id : ''}}</p>
                            <p>Municipality: {{ isset($data->contactDetail) ? $data->contactDetail->municipality_id : ''}}</p>
                            <p>Tole/Landmark:{{ isset($data->contactDetail) ? $data->contactDetail->tole : ''}}</p>
                        </div>
                        <div class="col-md-6">
                            <p>District: {{ isset($data->contactDetail) ? $data->contactDetail->district_id : ''}}</p>
                            <p>Ward: {{ isset($data->contactDetail) ? $data->contactDetail->ward : ''}}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Current Location Name (fill only if the contact is temporarily staying in a quarantine facility, hotel or
                        similar place)
                    </td>
                </tr>
                <tr>
                    <td>Telephone (mobile) number: {{ isset($data->contactDetail) ? $data->contactDetail->emergency_contact_one : ''}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Alternative Contact number: {{ isset($data->contactDetail) ? $data->contactDetail->emergency_contact_two : ''}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Interview respondent information (if the persons providing the information is not the contact)</b>
                    </td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td>Relationshi[ to the contact:</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>Mobile no.</td>
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
                                    <input type="checkbox" id="yes" name="yes" value=""  @if(isset($data->contactDetail) && $data->contactDetail->symptoms_recent == 1) checked readonly @else disabled @endif/>
                                    <label for="yes"> Yes</label>
                                </div>
                                <div style="padding-left: 1em;">
                                    <input type="checkbox" id="no" name="no" value="" @if( isset($data->contactDetail) && $data->contactDetail->symptoms_recent != 1) checked readonly @else disabled @endif/>
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
                                    <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                                    <label for="Yes"> Yes</label>
                                </div>
                                <div style="padding-left: 1em;">
                                    <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                                    <label for="No"> No</label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>If answer to 3.1 or 3.2 is Yes,</b><br>
                        <b>Date of Onset of First set of Symptoms [dd/mm/yyyy]: {{ isset($data->contactDetail) ? $data->contactDetail->symptoms_date : ''}}</b><br>
                        <b>Check any and all applicable symptoms listed below</b>
                        <div>
                        <div class=" col-md-4">
                            <div>
                                <input type="checkbox" id="Fever" name="Fever" value="t" @if(in_array(4, $symptomscontactdetail)) checked @endif readonly/>
                                <label for="Fever">Fever</label>                                           
                            </div>
                            <div>
                                <input type="checkbox" id="muscles" name="muscles" value="" @if(in_array(13, $symptomscontactdetail)) checked @endif readonly/>
                                <label for="muscles">Pain in the muscles</label>
                            </div>
                            <div>
                                <input type="checkbox" id="" name="" value="" readonly @if(in_array(13, $symptomscontactdetail)) checked @endif readonly/>
                                <label for="">Nausea / Vomiting / Loss of appetite </label>
                            </div>
                            <div>
                                <input type="checkbox" id="lossoftaste" name="lossoftaste" value="" @if(in_array(11, $symptomscontactdetail)) checked @endif readonly/>
                                <label for="lossoftaste"> Recent loss of taste</label>
                            </div>
                        </div>
                            <div class="col-md-2">
                                <div>
                                    <input type="checkbox" id="Cough" name="Cough" value="" @if(in_array(6, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Cough"> Cough</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="Sorethroat" name="Sorethroat" value="" @if(in_array(7, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Sorethroat"> Sore throat</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="Diarrhoea" name="Diarrhoea" value="" @if(in_array(17, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Diarrhoea"> Diarrhoea</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <input type="checkbox" id="Tiredness" name="Tiredness" value="" @if(in_array(5, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Tiredness"> General weakness/Tiredness</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="runnynose" name="runnynose" value="" @if(in_array(8, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="runnynose"> Runny nose </label>
                                </div>
                                <div>
                                    <input type="checkbox" id="Irritability/Confusion" name="Irritability/Confusion" value="" @if(in_array(10, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Irritability/Confusion"> Irritability/Confusion</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="specify" name="specify" value="" @if(isset($data->contactDetail) && !is_null($data->contactDetail->symptoms_specific)) checked @endif readonly/>
                                    <label for="specify"> Others, specify: <span style="font-weight: 500 !important;">{{ isset($data->contactDetail) ? $data->contactDetail->symptoms_specific : ''}}</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <input type="checkbox" id="Headache" name="Headache" value="" @if(in_array(19, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="Headache"> Headache</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="shortbreath" name="shortbreath" value="" @if(in_array(9, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="shortbreath"> Shortness of breath</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="recent-loss-of-smell" name="recent-loss-of-smell" value="" @if(in_array(12, $symptomscontactdetail)) checked @endif readonly/>
                                    <label for="recent-loss-of-smell"> Recent loss of smell</label>
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
                            <div style="padding-left: 1em;">
                                <input  style="padding-left: 0.5em;" id="recent-loss-of-smell" type="checkbox" name="recent-loss-of-smell" value="" @if(in_array(5, $symptomsComorbidityContactDetail) || in_array(16, $symptomsComorbidityContactDetail) || in_array(17, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                                @if(in_array(5, $symptomsComorbidityContactDetail))
                                    <label for="recent-loss-of-smell">Pregnancy (trimester: <u>One</u> )</label>
                                @elseif(in_array(16, $symptomsComorbidityContactDetail))
                                    <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Two</u> )</label>
                                @elseif(in_array(17, $symptomsComorbidityContactDetail))
                                    <label for="recent-loss-of-smell">Pregnancy (trimester: <u>Three</u> )</label>
                                @else
                                <label for="recent-loss-of-smell">Pregnancy (trimester: <u></u> )</label>
                                @endif
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" id="post-delivery" type="checkbox" name="post-delivery" value="" />
                                <label for="post-delivery">Post-delivery <span> (<6 weeks) </span></label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" id="cardiovascular-disease" type="checkbox" name="cardiovascular-disease" value="" @if(in_array(7, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                                <label for="cardiovascular-disease">Cardiovascular disease, including hypertension</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" id="diabetes" name="diabetes" type="checkbox" value="" @if(in_array(1, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                                <label for="diabetes"> Diabetes</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div style="padding-left: 1em;">
                            <input style="padding-left: 0.5em;" id="malignancy" name="malignancy" type="checkbox" value="" @if(in_array(14, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                            <label for="malignancy"> Malignancy</label>
                        </div>
                        <div style="padding-left: 1em;">
                            <input style="padding-left: 0.5em;" id="COPD" name="COPD" type="checkbox" value="" @if(in_array(15, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                            <label for="COPD"> COPD</label>
                        </div>
                        <div style="padding-left: 1em;">
                            <input style="padding-left: 0.5em;" id="Chronic-Kidney-Diseases" name="Chronic-Kidney-Diseases" type="checkbox" value="" @if(in_array(10, $symptomsComorbidityContactDetail)) checked @endif readonly/>
                            <label for="Chronic-Kidney-Diseases"> Chronic Kidney Diseases</label>
                        </div>
                        <div style="padding-left: 1em;">
                            <input style="padding-left: 0.5em;" id="malignancy" name="malignancy" type="checkbox" value="" @if(isset($data->contactDetail) && !is_null($data->contactDetail->symptoms_comorbidity_specific)) checked @endif readonly/>
                            <label for="malignancy"> Others, specify: <span style="font-weight: 500 !important;">{{ isset($data->contactDetail) ? $data->contactDetail->symptoms_comorbidity_specific : ''}}</span></label>
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
                        <div >
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="health-worker" name="health-worker" value="">
                                <label for="health-worker"> Health worker</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="working-with-animals" name="working-with-animals" value="">
                                <label for="working-with-animals"> Working with animals</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="health-lab-worker" name="health-lab-worker" value="">
                                <label for="health-lab-worker"> Health laboratory worker</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="student-teacher" name="student-teacher" value="">
                                <label for="student-teacher"> Student/Teacher</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="security-personnel" name="security-personnel" value="">
                                <label for="security-personnel"> Security Personnel</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="waste-management-worker" name="waste-management-worker" value="">
                                <label for="waste-management-worker"> Waste Management Worker</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="hotel-resturant" name="hotel-resturant" value="">
                                <label for="hotel-resturant"> Hotel/Restaurant/Bars</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="other-specify" name="other-specify" value="">
                                <label for="other-specify"> Other, specify:</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="security-personnel" name="security-personnel" value="">
                                <label for="security-personnel"> Security Personnel</label>
                            </div>
                            <div style="padding-left: 1em;">
                                <p>For each occupation, please specify location or facility:  <u>___</u> </p>
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
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="">
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value="">
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel (dd/mm/yyyy): ___/___/___ to ___/___/___</p>
                            <p>Mode of travel: Flight/ Public vehicle/Privatevehicle</p>
                            <p>Place visited: <u>___</u> </p>
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
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="">
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value="">
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel (dd/mm/yyyy): ___/___/___ to ___/___/___</p>
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
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="">
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value="">
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-8" style="padding-left: 0.5;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                            <p>If Yes, dates of travel (dd/mm/yyyy): ___/___/___ to ___/___/___</p>
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
                        <p>(specify):____________</p>
                    </td>
                    <td width="50%">
                        <p>Name of the work place: <u>____</u> </p>
                        <p>Station: Fever Clinic/ Isolation ward/ ICU/ Lab/ Other
                            (specify) <u>__</u>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Was appropriate PPE used?</p>
                        <div>
                            <div class="col-md-1" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="yes" name="yes" value="">
                                <label for="yes"> Yes</label>
                            </div>
                            <div class="col-md-1" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="no" name="no" value="">
                                <label for="no"> No</label>
                            </div>
                            <div class="col-md-10" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                            <div class="col-md-12" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> If No, Specify <u>___</u> </label>
                            </div>
                        </div>
        </div>
        </td>
        </tr>
        <tr>
            <td>Date of first contact (dd/mm/yy):</td>
            <td>Date of last contact (dd/mm/yy)</td>
        </tr>
        <tr>
            <td colspan="2">Any relevant narrative:</td>
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
                <th width="20%">Yes</th>
                <th colspan="2">No</th>
                <th colspan="2">Unknown</th>
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
                <td></td>
                <td></td>
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
                <td></td>
                <td></td>
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
                            <input style="padding-left: 0.5em;" type="checkbox" id="contact-admitted" name="contact-admitted" value="">
                            <label for="contact-admitted"> Contact admitted to hospital</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="quarantine-center" name="quarantine-center" value="">
                            <label for="quarantine-center">Referred to Quarantine Center</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="home-quarantine" name="home-quarantine" value="">
                            <label for="home-quarantine"> Home Quarantine</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="contact-lost" name="contact-lost" value="">
                            <label for="contact-lost"> Contact lost</label>
                        </div>
                        <div style="padding-left: 0.5em;">
                            <input style="padding-left: 0.5em;" type="checkbox" id="other-specify" name="other-specify" value="">
                            <label for="other-specify"> Other (specify) _____</label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-md-12">
                        <p>If referred to hospital/quarantine facility:</p>
                        <p>Referred date:</p>
                        <p>Name of hospital/ /Quarantine Centre: _____</p>
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
                                <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                                <label for="Yes"> Yes</label>
                            </div>
                            <div class="col-md-1" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                                <label for="No"> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p>If yes, test conducted</p>
                        <div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="RT-PCR" name="RT-PCR" value="">
                                <label for="RT-PCR"> RT-PCR</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="RDT- Antigen " name="RDT- Antigen " value="">
                                <label for="RDT- Antigen "> RDT- Antigen test</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p>If yes, date of swab collection(dd/mm/yy):__________________</p>
                        <div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="test-result" name="test-result" value="">
                                <label for="test-result"> Test Results:</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="RT-PCR" name="RT-PCR" value="">
                                <label for="RT-PCR"> RT-PCR</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="RDT- Antigen " name="RDT- Antigen " value="">
                                <label for="RDT- Antigen "> RDT- Antigen test</label>
                            </div>
                            <div class="col-md-2" style="padding-left: 0.5em;">
                                <input style="padding-left: 0.5em;" type="checkbox" id="unknown" name="unknown" value="">
                                <label for="unknown"> Unknown</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p>If positive, test result date (dd/mm/yy)_________________</p>
                    </div>
                </td>
            </tr>
        </table>
        <table style="margin-top: 1em;">
            <tr>
                <th colspan="2" style="background-color:#8eaadb">11. Data collector information</th>
            </tr>
            <tr>
                <td width="50%">Name:</td>
                <td width="50%">Institute</td>
            </tr>
            <tr>
                <td width="50%">Telephone number:</td>
                <td width="50%">Email: </td>
            </tr>
            <tr>
                <td width="50%">Form completion date (dd/mm/yy):</td>
                <td width="50%"></td>
            </tr>
        </table>
    </div>
    <div class="form-b2">
        <h4>Annex 3: Form B2 – Contact Follow-up Form/Symptoms Diary</h4>
        <table style="margin-top: 1em;">
            <tr>
                <th colspan="2" style="background-color:#8eaadb">1. Case Information</th>
            </tr>
            <tr>
                <td>Name of the case _____</td>
                <td>EPID ID ______</td>
            </tr>
        </table>
        <table>
            <tr>
                <th colspan="2" style="background-color:#8eaadb">2. Contact Information</th>
            </tr>
            <tr>
                <td width="75%">Name</td>
                <td width="25%">EPID ID ______</td>
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
            <tr>
                <td style="text-align: center;">0</td>
                <td style="text-align: center;">
                    <p>10</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">1</td>
                <td style="text-align: center;">
                    <p>9</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td style="text-align: center;">
                    <p>8</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td style="text-align: center;">
                    <p>7</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td style="text-align: center;">
                    <p>6</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td style="text-align: center;">
                    <p>5</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td style="text-align: center;">
                    <p>4</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td style="text-align: center;">
                    <p>3</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td style="text-align: center;">
                    <p>2</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td style="text-align: center;">
                    <p>1</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td style="text-align: center;">
                    <p>0</p>
                    <img style="margin-left: -8em;" width="100px"height="20px" class="arrow-img" src="{{ asset('images/arrow.png') }}" alt="">
                </td>
                <td></td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="none" name="none" value="">
                        <label for="none"> None</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td width="7%">
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="Yes" name="Yes" value="">
                        <label for="Yes"> Yes</label>
                    </div>
                    <div style="padding-left: 0.5em;">
                        <input style="padding-left: 0.5em;" type="checkbox" id="No" name="No" value="">
                        <label for="No"> No</label>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
        <div style="margin-top: 0.5em; margin-bottom: 1em;" class="col-md-12">
            <p>* Follow-up should start from the day it has been since last contact with the case. For e.g., if the contact has not been in contact with the case
                since 12 days, the follow-up should start from the 12th day in the column “Days to follow up”
            </p>
            <p>** Please select None for No symptoms. If no symptoms are experienced, then consider the entry comple</p>
        </div>
        <table>
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
                <td width="50%">Name: {{ isset($data->registerBy) ? $data->registerBy->name : '' }}</td>
                <td width="50%">Institution: {{ isset($data->registerBy) ? $data->healthpost->name : '' }}</td>
            </tr>
            <tr>
                <td>Telepphone number:{{ isset($data->registerBy) ? $data->registerBy->phone : '' }}</td>
                <td>Email</td>
            </tr>
            <tr>
                <td>Form completion date(dd/mm/yyyy):</td>
                <td></td>
            </tr>
        </table>
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
