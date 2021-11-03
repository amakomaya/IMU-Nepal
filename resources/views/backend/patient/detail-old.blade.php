@extends('layouts.backend.app')
@section('content')
    <style>
        .main {
            width: 90%;
            margin: 0 auto;
        }

        .header {
            font-family: sans-serif;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .date {
            color: #000;
            margin-top: 10px;
            font-size: 17px;
        }

        *, p {
            margin: 0;
            padding: 0;
        }

        .img {
            text-align: right;
        }

        .titleMid {
            text-align: center;
        }

        .titleSide {
            color: #E61C23;
            font-size: 15px;
        }

        .govF {
            color: #E61C23;
            font-size: 14px;
        }

        .govM {
            color: #E61C23;
            font-size: 17px;
        }

        .govB {
            color: #E61C23;
            font-weight: bolder;
            font-size: large;
        }

        .govA {
            color: #E61C23;
            font-size: 14px;
        }


        .titleHead {
            font-size: 18px;
            padding-top: 20px;
            font-weight: 700;
        }

        .subTitle {
            padding-top: 20px;
            font-size: 15px;
        }

        .typeSample {
            padding-top: 20px;
            border: 1px solid #000;
        }

        .noteStyle {
            display: flex;
            margin-top: 20px;
        }

        .footerStyle {
            margin-top: 20px;
            font-size: 14px;
        }

        @media print
        {    
            .submit-btn
            {
                display: none !important;
            }
        }

    </style>
    <div id="page-wrapper">
        <div class="row">
            <div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary btn-lg submit-btn">
                        <i class="fa fa-print"> Print </i>
                    </button>
                </div>
                <div class="main" id="report-printMe">
                    <div class="header">
                        <div class="img">
                            <img src="/images/report-logo.jpg" width="92" height="92" alt="">
                        </div>

                        <div class="titleMid">
                            <p class="govF">Government of Nepal</p>
                            <p class="govF">Ministry of Health & Population</p>
                            <p class="govM">Department of Health Service</p>
                        </div>

                        <div class="titleSide">
                            <p>Phone: {{  $data[0]->healthpost_phone }}</p>
                            <p class="date">Date: {{  $data[0]->healthpost_created_at }}</p>
                        </div>
                    </div>
                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <h4>Personal Information </h4>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Case ID :</td>
                            <td>
                                {{ $data[0]->case_id }} <br>
                                @if($data[0]->case_type == 2)
                                    Old or Follow Up Case
                                @else
                                    New Case
                                @endif

                            </td>

                        </tr>
                        <tr>
                            <td>Name :</td>
                            <td>{{ $data[0]->name }}</td>
                        </tr>
                        <tr>
                            <td>Age :</td>
                            <?php
                                if($data[0]->age == '1'){
                                    $age_unit = 'Months';
                                }elseif($data[0]->age == '2'){
                                    $age_unit = 'Days';
                                }else {
                                    $age_unit = 'Years';
                                }
                            ?>
                            <td>{{ $data[0]->age }} / <span> {{ $age_unit }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Gender :</td>
                            <?php
                                if($data[0]->sex == '1'){
                                    $formated_gender = 'Male';
                                }elseif($data[0]->sex == '2'){
                                    $formated_gender = 'Female';
                                }else {
                                    $formated_gender = "Don\'t Know";
                                }
                            ?>
                            <td>{{ $formated_gender }}
                        </tr>
                        <tr>
                            <td>Ethnicity :</td>
                            <?php
                                if($data[0]->caste == '1'){
                                    $formatted_caste = 'Dalit';
                                }elseif($data[0]->caste == '2'){
                                    $formatted_caste =  'Janajati';
                                }elseif($data[0]->caste ==  '3'){
                                    $formatted_caste = 'Madhesi';
                                }elseif($data[0]->caste ==  '4'){
                                    $formatted_caste =  'Muslim';
                                }elseif($data[0]->caste ==  '5'){
                                    $formatted_caste =  'Brahmin / Chhetri';
                                }elseif($data[0]->caste ==  '6'){
                                    $formatted_caste =  'Other';
                                }else{
                                    $formatted_caste =  "Don't Know";
                                }
                            ?>
                            <td>{{ $formatted_caste }}

                        </tr>
                        <tr>
                            <td>Emergency Phone :</td>
                            <td>
                                @if(is_null($data[0]->emergency_contact_one))
                                @else
                                    One : {{ $data[0]->emergency_contact_one }} <br>
                                @endif
                                @if(is_null($data[0]->emergency_contact_two))
                                @else
                                    Two : {{ $data[0]->emergency_contact_two }} <br>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Occupation :</td>
                            <?php
                                if($data[0]->occupation == '1'){
                                    $formatted_occupation = 'Front Line Healthworker';
                                }
                                elseif($data[0]->occupation == '2'){
                                    $formatted_occupation = 'Doctor';
                                }
                                elseif($data[0]->occupation == '3'){
                                    $formatted_occupation = 'Nurse';
                                }
                                elseif($data[0]->occupation == '4'){
                                    $formatted_occupation = 'Police / Army';
                                }
                                elseif($data[0]->occupation == '5'){
                                    $formatted_occupation = 'Business / Industry';
                                }
                                elseif($data[0]->occupation == '6'){
                                    $formatted_occupation = 'Teacher / Student ( Education )';
                                }
                                elseif($data[0]->occupation == '7'){
                                    $formatted_occupation = 'Civil Servant';
                                }
                                elseif($data[0]->occupation == '8'){
                                    $formatted_occupation = 'Journalist';
                                }
                                elseif($data[0]->occupation == '9'){
                                    $formatted_occupation = 'Agriculture';
                                }
                                elseif($data[0]->occupation == '10'){
                                    $formatted_occupation = 'Transport / Delivery';
                                }
                                else{
                                    $formatted_occupation = 'Other';
                                }
                            ?>
                            <td>{{ $formatted_occupation }}</td>
                        </tr>
                        <tr>
                            <td>Address :</td>
                            <td>
                                Province : {{ $data[0]->province_name }}<br>
                                Municipality : {{ $data[0]->municipality_name }}<br>
                                District : {{ $data[0]->district_name }}<br>
                                {{ $data[0]->tole }} - {{ $data[0]->ward }}
                            </td>
                        </tr>
                        <tr>
                            <td>Date of onset of first symptoms :</td>
                            <td>{{ $data[0]->date_of_onset_of_first_symptom }}</td>
                        </tr>
                        <tr>
                            <td>Case :</td>
                            <td>
                                @if($data[0]->cases == 1)
                                    Asymptomatic / Mild Case <br>
                                    @if($data[0]->case_where == 1)
                                        Home
                                    @elseif($data[0]->case_where == 2)
                                        Hotel
                                    @elseif($data[0]->case_where == 3)
                                        Institution
                                    @endif
                                @elseif($data[0]->cases == 2)
                                    Moderate / Sever Case <br>
                                    @if($data[0]->case_where == 1)
                                        General Ward
                                    @elseif($data[0]->case_where == 2)
                                        ICU
                                    @elseif($data[0]->case_where == 3)
                                        Ventilator
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Register By : </td>
                            <td>{{ $data[0]->health_workers_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>End Case :</td>
                            <td>
                                @if($data[0]->end_case == 1)
                                    Discharge
                                @elseif($data[0]->end_case == 2)
                                    Death
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Created At :</td>
                            <td>{{ date('Y-m-d', strtotime($data[0]->created_at)) }} </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                    @if($data[0]->sample_collection_count > 0)
                        <h4>Sample Collection Information </h4>
                        @foreach ($data as $anc)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <h5>Sample Collected Date: {{ $anc->sample_collection_created_at ?? '' }} </h5>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>SID :</td>
                                    <td>{{ $anc->sample_collection_token ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Test</td>
                                    <td>
                                        @if($anc->sample_collection_service_for == "2")
                                            Rapid Antigen Test
                                        @else
                                            SARS-CoV-2 RNA Test
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sample Type :</td>
                                    <td>
                                        @if(is_null($anc->sample_collection_sample_type))
                                        @else
                                            @if($anc->sample_collection_sample_type == "1")
                                                Nasopharyngeal
                                            @elseif($anc->sample_collection_sample_type == "2")
                                                Oropharyngeal
                                            @endif
                                            <?php
                                                $contains_nasopharyngeal = Illuminate\Support\Str::contains($anc->sample_collection_sample_type, ['1']);
                                                $contains_oropharyngeal = Illuminate\Support\Str::contains($anc->sample_collection_sample_type, ['2']);

                                                $sample_collection_sample_type_name = '';

                                                if ($contains_nasopharyngeal){
                                                    $sample_collection_sample_type_name = 'Nasopharyngeal';
                                                }

                                                if ($contains_oropharyngeal){
                                                    $sample_collection_sample_type_name = 'Oropharyngeal';
                                                }

                                                if ($contains_nasopharyngeal && $contains_oropharyngeal){
                                                    $sample_collection_sample_type_name = 'Nasopharyngeal, Oropharyngeal';
                                                }

                                            ?>
                                            {{ $sample_collection_sample_type_name }}
                                        @endif
                                        @if(is_null($anc->sample_collection_sample_type_specific))
                                        @else
                                            /{{ $anc->sample_collection_sample_type_specific }}
                                        @endif
                                    </td>
                                </tr>
                                <td>Infection Type</td>
                                <?php
                                    if($anc->sample_collection_infection_type == '1'){
                                        $formatted_sample_collection_infection_type = 'Symptomatic';
                                    }else{
                                        $formatted_sample_collection_infection_type = 'Asymptomatic';
                                    }
                                ?>
                                <td>{{ $formatted_sample_collection_infection_type }}</td>
                                <tr>
                                    <td>Service Type :</td>
                                    @if($anc->sample_collection_service_type == "1")
                                        <td>Paid</td>
                                    @else
                                        <td>Free of cost service</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Collected By :</td>
                                    <td>{{ $anc->sample_collection_checked_by_name }}</td>
                                </tr>
                                <tr>
                                    <td>Result :</td>
                                    <?php
                                        if($anc->sample_collection_result == 2){
                                            $formatted_sample_collection_result = 'Pending';
                                        } elseif($anc->sample_collection_result ==  3){
                                            $formatted_sample_collection_result = 'Positive';
                                        }
                                        elseif($anc->sample_collection_result ==  4){
                                            $formatted_sample_collection_result = 'Negative';
                                        }
                                        elseif($anc->sample_collection_result ==  9){
                                            $formatted_sample_collection_result = 'Received';
                                        }
                                        else {
                                            $formatted_sample_collection_result = 'Don\'t Know';
                                        }
                                    ?>
                                    <td>{{ $formatted_sample_collection_result }}</td>
                                </tr>
                                @if($anc->lab_tests_checked_by_name)
                                    <tr>
                                        <td>Tested By :</td>
                                        <td>{{ $anc->lab_tests_checked_by_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sample received date by lab :</td>
                                        <td>{{ $anc->lab_tests_sample_recv_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sample test date and time by lab :</td>
                                        <td>{{ $anc->lab_tests_sample_test_date }} {{ $anc->lab_tests_sample_test_time }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endforeach
                        <hr>
                    @endif
                    <br>
                    {{-- @if($data[0]->symptomsRelation()->count() > '0' || $data[0]->laboratoryParameter()->count() > '0' || $data[0]->clinicalParameter()->count() > '0')
                        <h4>Daily Health Checkup </h4>
                    @endif --}}
                    {{-- @if($data[0]->symptomsRelation->count() > '0')<h5>Symptom</h5>
                    @foreach ($data[0]->symptomsRelation as $symptom)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <h5>Created At : {{ $symptom->created_at->format('Y-m-d') }} </h5>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Day of hospital stay :</td>
                                <td>{{ $symptom->day }}</td>
                            </tr>
                            <tr>
                                <td>Fever</td>
                                <td>
                                    @if($symptom->fever == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Caugh</td>
                                <td>
                                    @if($symptom->caugh == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Chills</td>
                                <td>
                                    @if($symptom->chills == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Fatigue</td>
                                <td>
                                    @if($symptom->fatigue == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Muscle Pain</td>
                                <td>
                                    @if($symptom->mauscle_pain == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Headache</td>
                                <td>
                                    @if($symptom->headche == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Loss of smell</td>
                                <td>
                                    @if($symptom->loss_of_smell == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Loss of taste</td>
                                <td>
                                    @if($symptom->loss_of_taste == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Diarrhea</td>
                                <td>
                                    @if($symptom->diarrhea == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Sore Throat</td>
                                <td>
                                    @if($symptom->sore_throat == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>SOB</td>
                                <td>
                                    @if($symptom->sob == "1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td>
                                    {{ $symptom->others }}
                                </td>
                            </tr>
                            <tr>
                                <td>Checked By</td>
                                <td>
                                    {{ $symptom->checked_by_name }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endforeach
                    <hr>
                    @endif --}}
                    {{-- @if($data[0]->laboratoryParameter->count() > '0')<h5>Laboratory Parameter</h5>
                    @foreach ($data[0]->laboratoryParameter as $lp)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <h5>Created At : {{ $lp->created_at->format('Y-m-d') }} </h5>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Day of hospital stay :</td>
                                <td>{{ $lp->day }}</td>
                            </tr>
                            <tr>
                                <td>TC</td>
                                <td>
                                    {{ $lp->tc }}
                                </td>
                            </tr>
                            <tr>
                                <td>DC</td>
                                <td>
                                    {{ $lp->dc }}
                                </td>
                            </tr>
                            <tr>
                                <td>Creatinine</td>
                                <td>
                                    {{ $lp->creatinine }}
                                </td>
                            </tr>
                            <tr>
                                <td>ALT</td>
                                <td>
                                    {{ $lp->alt }}
                                </td>
                            </tr>
                            <tr>
                                <td>RBS</td>
                                <td>
                                    {{ $lp->rbs }}
                                </td>
                            </tr>
                            <tr>
                                <td>CRP</td>
                                <td>
                                    {{ $lp->crp }}
                                </td>
                            </tr>
                            <tr>
                                <td>PT</td>
                                <td>
                                    {{ $lp->pt }}
                                </td>
                            </tr>
                            <tr>
                                <td>D dimer</td>
                                <td>
                                    {{ $lp->d_dimer }}
                                </td>
                            </tr>
                            <tr>
                                <td>LDH</td>
                                <td>
                                    {{ $lp->ldh }}
                                </td>
                            </tr>
                            <tr>
                                <td>Ferritin</td>
                                <td>
                                    {{ $lp->ferritin }}
                                </td>
                            </tr>
                            <tr>
                                <td>Blood C/S</td>
                                <td>
                                    {{ $lp->blood }}
                                </td>
                            </tr>
                            <tr>
                                <td>X ray</td>
                                <td>
                                    {{ $lp->xray }}
                                </td>
                            </tr>
                            <tr>
                                <td>CT</td>
                                <td>
                                    {{ $lp->ct }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endforeach
                    <hr>
                    @endif --}}
                    {{-- @if($data[0]->clinicalParameter->count() > '0')<h5>Clinical Parameter</h5>@endif<hr>
                    @foreach ($data[0]->clinicalParameter as $row)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <h5>Created At : {{ $row->created_at->format('Y-m-d') }} </h5>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Day of hospital stay :</td>
                                <td>{{ $row->day }}</td>
                            </tr>
                            <tr>
                                <td>Temperature in Fahrenheit</td>
                                <td>
                                    {{ $row->temperature }}
                                </td>
                            </tr>
                            <tr>
                                <td>Pulse in beats per minute</td>
                                <td>
                                    {{ $lp->pulse }}
                                </td>
                            </tr>
                            <tr>
                                <td>BP in mmHg</td>
                                <td>
                                    {{ $lp->bp }}
                                </td>
                            </tr>
                            <tr>
                                <td>Respiratory rate in breath per minute</td>
                                <td>
                                    {{ $lp->respiratory }}
                                </td>
                            </tr>
                            <tr>
                                <td>SPO2 in Percentage</td>
                                <td>
                                    {{ $lp->spo2 }}
                                </td>
                            </tr>
                            <tr>
                                <td>Chest crepts (U/BL)</td>
                                <td>
                                    {{ $lp->chest_crepts }}
                                </td>
                            </tr>
                            <tr>
                                <td>Checked By</td>
                                <td>
                                    {{ $lp->checked_by_name }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endforeach --}}
                    {{-- @if($data[0]->caseManagement)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <h4>Case Management </h4>
                            </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            <tr>
                                <td>In case of Symptomatic case, reference date 14 days <br>
                                    before symptoms appear or in case of Asymptomatic case, <br>
                                    reference date of 6 weeks before the date od sample collection
                                </td>
                                <td> From Date : {{ $data[0]->caseManagement->reference_date_from }} <br>
                                    To Date : {{ $data[0]->caseManagement->reference_date_to }}
                                </td>
                            </tr>
                            <tr>
                                <td>Have case been in contact with the person <br>
                                    that has recently come from covid-19 infected place ?
                                </td>
                                <td>
                                    @if($data[0]->caseManagement->contact_with_covid_place == "1")
                                        Yes, @if($data[0]->caseManagement->contact_travel == "1") Domestic Travel @endif
                                        @if($data[0]->caseManagement->contact_travel == "2") International Travel @endif
                                    @elseif($data[0]->caseManagement->contact_with_covid_place == "2")
                                        No
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Full Name :</td>
                                <td>{{ $data[0]->caseManagement->name }}</td>
                            </tr>
                            <tr>
                                <td> Relation :</td>
                                <td>{{ $data[0]->caseManagement->relation }}</td>
                            </tr>
                            <tr>
                                <td> Last meet date :</td>
                                <td>{{ $data[0]->caseManagement->last_meet_date }}</td>
                            </tr>
                            <tr>
                                <td> Name of place / Country :</td>
                                <td>{{ $data[0]->caseManagement->covid_infect_place }}</td>
                            </tr>
                            <tr>
                                <td> Have case gone to festival or crowd ? :</td>
                                <td>
                                    @if($data[0]->caseManagement->case_gone_festival == "1")
                                        Yes <br>
                                        {{ $data[0]->caseManagement->case_gone_festival_info }}
                                    @elseif($data[0]->caseManagement->case_gone_festival == "2")
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td> Have case come from person with same illness ? :</td>
                                <td>
                                    @if($data[0]->caseManagement->case_contact_same_illness == "1")
                                        Yes <br>
                                        {{ $data[0]->caseManagement->case_contact_same_illness_info }}
                                    @elseif($data[0]->caseManagement->case_contact_same_illness == "2")
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td> Have case gone to any institution / hospital / pharmacy ? :</td>
                                <td>
                                    @if($data[0]->caseManagement->case_gone_institution == "1")
                                        Yes <br>
                                        {{ $data[0]->caseManagement->case_gone_institution }}
                                    @elseif($data[0]->caseManagement->case_gone_institution == "2")
                                        No
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Any other information :</td>
                                <td>{{ $data[0]->caseManagement->case_additional_info }}</td>
                            </tr>
                            <tr>
                                <td>
                                    Checked By :
                                </td>
                                <td>{{ $data[0]->caseManagement->checked_by_name }}</td>
                            </tr>

                            </tbody>
                        </table>
                        <hr>

                    @endif --}}
                    {{-- @if($data[0]->contactTracing->count() > '0')
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <h4>Contact Tracing List</h4>
                            </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            <tr>
                                <th> S.N.</th>
                                <th> Details</th>
                            </tr>
                            @foreach($data[0]->contactTracing as $row)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td> Name : {{ $row->name }} <br>
                                        Ethnicity : {{ $data[0]->caste($row->caste) }}<br>
                                        Age : {{ $row->age }} <br>
                                        Contact Classification : @if($row->contact_classification == "1")
                                            Close
                                        @else
                                            Casual
                                        @endif
                                        <br>
                                        Relationship with case : {{ $row->case_relation }}<br>
                                        Emergency Phone :
                                        @if(is_null($row->emergency_contact_one))
                                        @else
                                            One : {{ $row->emergency_contact_one }} <br>
                                        @endif
                                        @if(is_null($row->emergency_contact_two))
                                        @else
                                            Two : {{ $row->emergency_contact_two }} <br>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif --}}
                </div>
            </div>
        </div>

        {{--    <div id="page-wrapper">--}}
        {{--        <div class="row">--}}
        {{--            <div class="col-lg-12">--}}
        {{--                <div id="app">--}}
        {{--                    <case-detail></case-detail>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        @endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script type="text/javascript">
        function getProvinceName(id) {
            $.get("{{ route("admin.district-value") }}?id=" + id, function (data) {
                console.log(data);
            })
        }
    </script>
    <script>
        $('.submit-btn').click(function(){
            window.print();
        });
    </script>
@endsection
