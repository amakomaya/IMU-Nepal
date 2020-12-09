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

        </style>
        <div id="page-wrapper">
            <div class="row">
                <div>
                    <div class="pull-right">
                        <button type="submit" id="print" class="btn btn-primary btn-lg">
                            <i class="fa fa-print"> Print </i>
                        </button>
                    </div>
                    <div class="main" id="printable">
                        <div class="header">
                            <div class="img">
                                <img src="/images/report-logo.jpg" width="92" height="92" alt="">
                            </div>

                            <div class="titleMid">
                                <p class="govF">Government of Nepal</p>
                                <p class="govF">Ministry of Health & Population</p>
                                <p class="govM">Department of Health Service</p>
                                <p class="govB">{{ $data->healthpost->name }}</p>
                                <p class="govA">{{ $data->healthpost->address }}
                                    , {{ $data->healthpost->district_id }}</p>
                            </div>

                            <div class="titleSide">
                                <p>Phone: {{  $data->healthpost->phone }}</p>
                                <p class="date">Date: {{  $data->healthpost->created_at }}</p>
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
                                    {{ $data->case_id }} <br>
                                    @if($data->case_type == 2)
                                        Old or Follow Up Case
                                    @else
                                        New Case
                                    @endif

                                </td>

                            </tr>
                            <tr>
                                <td>Name :</td>
                                <td>{{ $data->name }}</td>
                            </tr>
                            <tr>
                                <td>Age :</td>
                                <td>{{ $data->age }} / <span> {{ $data->formated_age_unit }} </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Gender :</td>
                                <td>{{ $data->formated_gender }}
                            </tr>
                            <tr>
                                <td>Caste :</td>
                                <td>{{ $data->caste($data->caste) }}

                            </tr>
                            <tr>
                                <td>Emergency Phone :</td>
                                <td>
                                    @if(is_null($data->emergency_contact_one))
                                    @else
                                        One : {{ $data->emergency_contact_one }} <br>
                                    @endif
                                    @if(is_null($data->emergency_contact_two))
                                    @else
                                        Two : {{ $data->emergency_contact_two }} <br>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Occupation :</td>
                                <td>{{ $data->occupation($data->occupation) }}</td>
                            </tr>
                            <tr>
                                <td>Address :</td>
                                <td>
                                    Province : {{ $data->province->province_name }}<br>
                                    Municipality : {{ $data->municipality->municipality_name }}<br>
                                    District : {{ $data->district->district_name }}<br>
                                    {{ $data->tole }} - {{ $data->ward }}
                                </td>
                            </tr>
                            <tr>
                                <td>Date of onset of first symptoms :</td>
                                <td>{{ $data->date_of_onset_of_first_symptom }}</td>
                            </tr>
                            <tr>
                                <td>Case :</td>
                                <td>
                                    @if($data->case == 1)
                                        Asymptomatic / Mild Case <br>
                                        @if($data->case_where == 1)
                                            Home
                                        @elseif($data->case_where == 2)
                                            Hotel
                                        @elseif($data->case_where == 3)
                                            Institution
                                        @endif
                                    @elseif($data->case == 2)
                                        Moderate / Sever Case <br>
                                        @if($data->case_where == 1)
                                            General Ward
                                        @elseif($data->case_where == 2)
                                            ICU
                                        @elseif($data->case_where == 3)
                                            Ventilator
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Register By : </td>
                                <td>{{ $data->registerBy->name }}</td>
                            </tr>
                            <tr>
                                <td>End Case :</td>
                                <td>
                                    @if($data->end_case == 1)
                                        Discharge
                                    @elseif($data->end_case == 2)
                                        Death
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Created At :</td>
                                <td>{{ $data->created_at->format('Y-m-d') }} </td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        @if($data->ancs->count() > 0)
                        <h4>Sample Collection Information </h4>
                        @foreach ($data->ancs as $anc)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <h5>Sample Collected Date: {{ $anc->created_at }} </h5>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>SID :</td>
                                    <td>{{ $anc->token }}</td>
                                </tr>
                                <tr>
                                    <td>Test</td>
                                    <td>
                                    @if($anc->service_for == "2")
                                            Rapid Antigen Test
                                    @else
                                            SARS-CoV-2 RNA Test
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sample Type :</td>
                                    <td>
                                        @if(is_null($anc->sample_type))
                                        @else
                                            {{ $anc->sampleCollectionType($anc->sample_type) }}
                                        @endif
                                        @if(is_null($anc->sample_type_specific))
                                        @else
                                            /{{ $anc->sample_type_specific }}
                                        @endif
                                    </td>
                                </tr>
                                    <td>Infection Type</td>
                                    <td>{{ $anc->infectionType($anc->infection_type) }}</td>
                                <tr>
                                    <td>Service Type :</td>
                                    @if($anc->service_type == "1")
                                        <td>Paid</td>
                                    @else
                                        <td>Free of cost service</td>
                                    @endif
                                </tr>
                                <tr>
                                     <td>Collected By :</td>
                                    <td>{{ $anc->checked_by_name }}</td>
                                </tr>
                                <tr>
                                    <td>Result :</td>
                                    <td>{{ $anc->formatted_result }}</td>
                                </tr>
                                @if($anc->labreport)
                                    <tr>
                                        <td>Tested By :</td>
                                        <td>{{ $anc->labreport->checked_by_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sample received date by lab :</td>
                                        <td>{{ $anc->labreport->sample_recv_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sample test date and time by lab :</td>
                                        <td>{{ $anc->labreport->sample_test_date }} {{ $anc->labreport->sample_test_time }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endforeach
                        @endif
                        <hr>
                        @if($data->symptomsRelation()->count() > 0 || $data->laboratoryParameter()->count() > 0 || $data->clinicalParameter()->count())
                            <h4>Daily Health Checkup </h4>
                        @endif
                        @if($data->symptomsRelation)<h5>Symptom</h5>
                        @foreach ($data->symptomsRelation as $symptom)
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
                            @endif
                        <hr>
                        @if($data->laboratoryParameter)<h5>Laboratory Parameter</h5>
                            @foreach ($data->laboratoryParameter as $lp)
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
                            @endif
                        <hr>
                        @if($data->clinicalParameter)<h5>Clinical Parameter</h5>
                        @foreach ($data->clinicalParameter as $row)
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
                        @endforeach
                        @endif
                        <hr>

                        @if($data->caseManagement)
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
                                            reference date of 6 weeks before the date od sample collection </td>
                                        <td> From Date : {{ $data->caseManagement->reference_date_from }} <br>
                                             To Date : {{ $data->caseManagement->reference_date_to }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Have case been in contact with the person <br>
                                            that has recently come from covid-19 infected place ? </td>
                                        <td>
                                            @if($data->caseManagement->contact_with_covid_place == "1")
                                                Yes, @if($data->caseManagement->contact_travel == "1") Domestic Travel @endif
                                                @if($data->caseManagement->contact_travel == "2") International Travel @endif
                                            @elseif($data->caseManagement->contact_with_covid_place == "2")
                                                No
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Full Name :</td>
                                        <td>{{ $data->caseManagement->name }}</td>
                                    </tr>
                                    <tr>
                                        <td> Relation : </td>
                                        <td>{{ $data->caseManagement->relation }}</td>
                                    </tr>
                                    <tr>
                                        <td> Last meet date : </td>
                                        <td>{{ $data->caseManagement->last_meet_date }}</td>
                                    </tr>
                                    <tr>
                                        <td> Name of place / Country : </td>
                                        <td>{{ $data->caseManagement->covid_infect_place }}</td>
                                    </tr>
                                    <tr>
                                        <td> Have case gone to festival or crowd ? : </td>
                                        <td>
                                            @if($data->caseManagement->case_gone_festival == "1")
                                                Yes <br>
                                                {{ $data->caseManagement->case_gone_festival_info }}
                                            @elseif($data->caseManagement->case_gone_festival == "2")
                                                No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Have case come from person with same illness ? : </td>
                                        <td>
                                            @if($data->caseManagement->case_contact_same_illness == "1")
                                                Yes <br>
                                                {{ $data->caseManagement->case_contact_same_illness_info }}
                                            @elseif($data->caseManagement->case_contact_same_illness == "2")
                                                No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Have case gone to any institution / hospital / pharmacy ? : </td>
                                        <td>
                                            @if($data->caseManagement->case_gone_institution == "1")
                                                Yes <br>
                                                {{ $data->caseManagement->case_gone_institution }}
                                            @elseif($data->caseManagement->case_gone_institution == "2")
                                                No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Any other information : </td>
                                        <td>{{ $data->caseManagement->case_additional_info }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Checked By :
                                        </td>
                                        <td>{{ $data->caseManagement->checked_by_name }}</td>
                                    </tr>

                                </tbody>
                            </table>

                        @endif
                        <hr>

                        @if($data->contactTracing->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <h4>Contact Tracing List</h4>
                                </tr>
                                </thead>
                                <tbody>
                                <tbody>
                                <tr>
                                    <th> S.N. </th>
                                    <th> Details </th>
                                </tr>
                                @foreach($data->contactTracing as $row)
                                    <tr>
                                        <td> {{ $loop->iteration }}</td>
                                        <td> Name : {{ $row->name }} <br>
                                             Caste : {{ $data->caste($row->caste) }}<br>
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

                        @endif




                    </div>
</div>

</div>
</div>
@endsection